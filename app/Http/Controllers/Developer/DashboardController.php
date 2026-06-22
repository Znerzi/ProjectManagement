<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ProjectAssignment;
use App\Models\ProjectTask;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // KPI Statistics
        $stats = [
            'total_assignments' => $user->developerAssignments()->count(),
            'active_assignments' => $user->developerAssignments()->where('status', 'working')->count(),
            'completed_assignments' => $user->developerAssignments()->completed()->count(),
            'total_tasks' => $user->developerTasks()->count(),
            'completed_tasks' => $user->developerTasks()->completed()->count(),
            'ongoing_tasks' => $user->developerTasks()->ongoing()->count(),
            'pending_tasks' => $user->developerTasks()->pending()->count(),
            'average_rating' => $user->average_rating,
            'completed_projects' => $user->completed_projects,
        ];

        // Tasks with urgency based on deadline
        $tasks = $user->developerTasks()
            ->with('project', 'developer')
            ->whereIn('status', ['pending', 'ongoing'])
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('deadline')
            ->get()
            ->map(function ($task) {
                $task->urgency = $this->calculateUrgency($task);
                $task->color = $task->getPriorityColor();
                $task->status_color = $task->getStatusColor();
                return $task;
            });

        // Calendar events (deadlines)
        $calendarEvents = $user->developerTasks()
            ->whereNotNull('deadline')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->module_name,
                    'start' => $task->deadline->toDateString(),
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'color' => $task->getPriorityColor(),
                ];
            });

        return response()->json([
            'stats' => $stats,
            'tasks' => $tasks,
            'calendar_events' => $calendarEvents,
        ]);
    }

    public function getAssignments()
    {
        $user = auth()->user();

        $assignments = $user->developerAssignments()
            ->with('project.client', 'project.tasks')
            ->get();

        return response()->json($assignments);
    }

    public function acceptAssignment(ProjectAssignment $assignment)
    {
        if ($assignment->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($assignment->status !== 'assigned') {
            return response()->json(['message' => 'Assignment is not in assignable state'], 422);
        }

        $assignment->update([
            'status' => 'working',
            'accepted_at' => now(),
        ]);

        return response()->json(['message' => 'Assignment accepted', 'assignment' => $assignment]);
    }

    public function rejectAssignment(Request $request, ProjectAssignment $assignment)
    {
        if ($assignment->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $assignment->update([
            'status' => 'rejected',
            'notes' => $validated['reason'],
        ]);

        return response()->json(['message' => 'Assignment rejected', 'assignment' => $assignment]);
    }

    public function getTasks()
    {
        $user = auth()->user();

        $tasks = $user->developerTasks()
            ->with('project', 'developer')
            ->orderByRaw("FIELD(status, 'pending', 'ongoing', 'completed')")
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('deadline')
            ->get();

        return response()->json($tasks);
    }

    public function updateTask(Request $request, ProjectTask $task)
    {
        if ($task->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,ongoing,completed',
            'progress' => 'required|integer|min:0|max:100',
            'technical_details' => 'nullable|string',
        ]);

        $oldProgress = $task->progress;
        $task->update($validated);

        // Update project progress if all tasks are completed
        if ($validated['status'] === 'completed') {
            $task->project->updateProgress();
        }

        return response()->json([
            'message' => 'Task updated',
            'task' => $task,
            'project_progress' => $task->project->progress,
        ]);
    }

    public function getTaskProgress(ProjectTask $task)
    {
        if ($task->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'task' => $task,
            'progress_percentage' => $task->progress,
            'status' => $task->status,
            'deadline' => $task->deadline,
            'urgency' => $this->calculateUrgency($task),
        ]);
    }

    public function updateTaskProgress(Request $request, ProjectTask $task)
    {
        if ($task->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $task->update($validated);

        // Auto-update task status based on progress
        if ($validated['progress'] === 100) {
            $task->update(['status' => 'completed']);
            $task->project->updateProgress();
        } elseif ($validated['progress'] > 0) {
            $task->update(['status' => 'ongoing']);
        }

        return response()->json([
            'message' => 'Progress updated',
            'task' => $task,
            'project_progress' => $task->project->progress,
        ]);
    }

    public function getTasksByStatus($status)
    {
        $user = auth()->user();

        $tasks = $user->developerTasks()
            ->where('status', $status)
            ->with('project')
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->get();

        return response()->json($tasks);
    }

    private function calculateUrgency(ProjectTask $task): string
    {
        if ($task->status === 'completed') {
            return 'completed';
        }

        if (!$task->deadline) {
            return 'low';
        }

        $daysLeft = $task->deadline->diffInDays(now());

        if ($daysLeft < 0) {
            return 'overdue';
        } elseif ($daysLeft === 0) {
            return 'today';
        } elseif ($daysLeft <= 3) {
            return 'urgent';
        } elseif ($daysLeft <= 7) {
            return 'high';
        } else {
            return 'normal';
        }
    }
}
