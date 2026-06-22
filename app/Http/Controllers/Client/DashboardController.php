<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Return dashboard stats and project list for the authenticated client.
     */
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'totalprojects'    => $user->clientProjects()->count(),
            'activeprojects'   => $user->clientProjects()->where('status', 'active')->count(),
            'completedprojects'=> $user->clientProjects()->where('status', 'completed')->count(),
            'pendingprojects'  => $user->clientProjects()->where('status', 'pending')->count(),
            'totalbudgetspent' => (float) ($user->clientProjects()->sum('budget') ?? 0),
        ];

        $projects = $user->clientProjects()
            ->with(['assignments.developer', 'tasks'])
            ->latest()
            ->get()
            ->map(function ($project) {
                // Compute progress from tasks if the model doesn't already have it
                if (! isset($project->progress)) {
                    $total    = $project->tasks->count();
                    $done     = $project->tasks->where('status', 'completed')->count();
                    $project->progress = $total > 0 ? (int) round(($done / $total) * 100) : 0;
                }
                return $project;
            });

        return response()->json([
            'stats'    => $stats,
            'projects' => $projects,
        ]);
    }

    /**
     * Return a single project with full relationships.
     */
    public function getProject(Project $project)
    {
        $this->authorizeProjectAccess($project);

        $project->load(['assignments.developer', 'tasks', 'histories']);

        // Compute progress if not on model
        if (! isset($project->progress)) {
            $total            = $project->tasks->count();
            $done             = $project->tasks->where('status', 'completed')->count();
            $project->progress = $total > 0 ? (int) round(($done / $total) * 100) : 0;
        }

        return response()->json($project);
    }

    /**
     * Create a new project for the authenticated client.
     */
    public function createProject(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget'      => 'required|numeric|min:0',
            'deadline'    => 'required|date|after:today',
            'startdate'   => 'nullable|date',
        ]);

        $project = auth()->user()->clientProjects()->create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'budget'      => $validated['budget'],
            'deadline'    => $validated['deadline'],
            'startdate'   => $validated['startdate'] ?? now(),
            'status'      => 'pending',
        ]);

        ProjectHistory::create([
            'projectid'   => $project->id,
            'userid'      => auth()->id(),
            'action'      => 'created',
            'description' => 'Project created by client.',
            'ipaddress'   => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Project created successfully.',
            'project' => $project,
        ], 201);
    }

    /**
     * Update an existing project owned by the authenticated client.
     */
    public function updateProject(Request $request, Project $project)
    {
        $this->authorizeProjectAccess($project);

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'budget'      => 'sometimes|numeric|min:0',
            'deadline'    => 'sometimes|date|after:today',
        ]);

        $oldData = $project->only(array_keys($validated));
        $project->update($validated);

        $changes = array_diff_assoc(
            $project->only(array_keys($validated)),
            $oldData
        );

        ProjectHistory::create([
            'projectid'   => $project->id,
            'userid'      => auth()->id(),
            'action'      => 'updated',
            'description' => 'Project updated by client.',
            'changes'     => $changes,
            'ipaddress'   => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Project updated successfully.',
            'project' => $project->fresh(),
        ]);
    }

    /**
     * Return progress details for a specific developer on a project.
     */
    public function getDeveloperProgress(Project $project, $developerId)
    {
        $this->authorizeProjectAccess($project);

        $assignment = $project->assignments()
            ->where('developerid', $developerId)
            ->with('developer')
            ->firstOrFail();

        $tasks = $project->tasks()
            ->where('developerid', $developerId)
            ->get();

        $total    = $tasks->count();
        $done     = $tasks->where('status', 'completed')->count();
        $progress = $total > 0 ? (int) round(($done / $total) * 100) : ($project->progress ?? 0);

        return response()->json([
            'assignment' => $assignment,
            'tasks'      => $tasks,
            'progress'   => $progress,
        ]);
    }

    /**
     * Return deadline/calendar data for a date range.
     * Useful if you want an AJAX calendar endpoint.
     */
    public function getCalendarDeadlines(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year'  => 'nullable|integer|min:2000|max:2100',
        ]);

        $month = $request->input('month', now()->month);
        $year  = $request->input('year',  now()->year);

        $deadlines = $user->clientProjects()
            ->whereMonth('deadline', $month)
            ->whereYear('deadline',  $year)
            ->where('status', '!=', 'completed')
            ->select('id', 'title', 'deadline', 'status')
            ->get()
            ->map(function ($p) {
                return [
                    'id'       => $p->id,
                    'title'    => $p->title,
                    'deadline' => $p->deadline,
                    'status'   => $p->status,
                    'day'      => (int) date('j', strtotime($p->deadline)),
                ];
            });

        return response()->json(['deadlines' => $deadlines]);
    }

    /**
     * Return the N most urgent active projects.
     */
    public function getUrgentProjects(Request $request)
    {
        $limit = min((int) $request->input('limit', 5), 20);

        $urgent = auth()->user()->clientProjects()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNotNull('deadline')
            ->orderBy('deadline', 'asc')
            ->limit($limit)
            ->with(['assignments.developer'])
            ->get();

        return response()->json(['urgent' => $urgent]);
    }

    // ─────────────────────────────────────────────────────────────
    //  Private helpers
    // ─────────────────────────────────────────────────────────────

    /**
     * Abort with 403 if the authenticated user does not own the project.
     */
    private function authorizeProjectAccess(Project $project): void
    {
        if ($project->clientid !== auth()->id()) {
            abort(403, 'You do not have access to this project.');
        }
    }
}