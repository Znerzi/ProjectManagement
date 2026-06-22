<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_developers' => User::where('role', 'developer')->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::active()->count(),
            'completed_projects' => Project::completed()->count(),
            'pending_projects' => Project::pending()->count(),
            'total_tasks' => ProjectTask::count(),
            'completed_tasks' => ProjectTask::completed()->count(),
            'overdue_tasks' => ProjectTask::whereDate('deadline', '<', now())->where('status', '!=', 'completed')->count(),
        ];

        $recentProjects = Project::with('client')->latest()->limit(10)->get();
        $urgentTasks = ProjectTask::urgent()->with('developer', 'project')->limit(10)->get();
        $systemActivity = \App\Models\ProjectHistory::recent()->limit(20)->get();

        return response()->json([
            'stats' => $stats,
            'recent_projects' => $recentProjects,
            'urgent_tasks' => $urgentTasks,
            'system_activity' => $systemActivity,
        ]);
    }

    public function getAllProjects(Request $request)
    {
        $query = Project::with('client', 'tasks', 'developers');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->paginate(15));
    }

    public function getAllUsers(Request $request)
    {
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->paginate(15));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update($validated);

        return response()->json(['message' => 'User status updated', 'user' => $user]);
    }

    public function updateProjectStatus(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,in_progress,completed,on_hold',
        ]);

        $project->update($validated);

        // Log to project history
        \App\Models\ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'description' => 'Status changed to ' . $validated['status'],
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['message' => 'Project status updated', 'project' => $project]);
    }

    public function assignProjectToDeveloper(Request $request, Project $project)
    {
        $validated = $request->validate([
            'developer_id' => 'required|exists:users,id',
        ]);

        $developer = User::findOrFail($validated['developer_id']);

        if (!$developer->isDeveloper()) {
            return response()->json(['message' => 'Selected user is not a developer'], 422);
        }

        $assignment = $project->assignments()->create([
            'developer_id' => $validated['developer_id'],
            'status' => 'assigned',
        ]);

        return response()->json(['message' => 'Developer assigned', 'assignment' => $assignment], 201);
    }
}
