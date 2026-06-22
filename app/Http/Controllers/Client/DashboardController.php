<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectAssignment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_projects' => $user->clientProjects()->count(),
            'active_projects' => $user->clientProjects()->active()->count(),
            'completed_projects' => $user->clientProjects()->completed()->count(),
            'pending_projects' => $user->clientProjects()->pending()->count(),
            'total_budget_spent' => $user->clientProjects()->sum('budget') ?? 0,
        ];

        $projects = $user->clientProjects()
            ->with('assignments.developer', 'tasks')
            ->latest()
            ->get();

        return response()->json([
            'stats' => $stats,
            'projects' => $projects,
        ]);
    }

    public function getProject(Project $project)
    {
        $this->authorizeProjectAccess($project);

        $project->load('assignments.developer', 'tasks', 'histories');

        return response()->json($project);
    }

    public function createProject(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'start_date' => 'nullable|date',
        ]);

        $project = auth()->user()->clientProjects()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'budget' => $validated['budget'],
            'deadline' => $validated['deadline'],
            'start_date' => $validated['start_date'] ?? now(),
            'status' => 'pending',
        ]);

        \App\Models\ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'description' => 'Project created',
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['message' => 'Project created', 'project' => $project], 201);
    }

    public function updateProject(Request $request, Project $project)
    {
        $this->authorizeProjectAccess($project);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'budget' => 'numeric|min:0',
            'deadline' => 'date|after:today',
        ]);

        $oldData = $project->toArray();
        $project->update($validated);

        \App\Models\ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'updated',
            'description' => 'Project updated',
            'changes' => array_diff_assoc($project->toArray(), $oldData),
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['message' => 'Project updated', 'project' => $project]);
    }

    public function getDeveloperProgress(Project $project, $developerId)
    {
        $this->authorizeProjectAccess($project);

        $assignment = $project->assignments()
            ->where('developer_id', $developerId)
            ->with('developer')
            ->firstOrFail();

        $tasks = $project->tasks()
            ->where('developer_id', $developerId)
            ->get();

        return response()->json([
            'assignment' => $assignment,
            'tasks' => $tasks,
            'progress' => $project->progress,
        ]);
    }

    private function authorizeProjectAccess(Project $project)
    {
        if ($project->client_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}
