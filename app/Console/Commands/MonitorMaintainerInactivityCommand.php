<?php

namespace App\Console\Commands;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class MonitorMaintainerInactivityCommand extends Command
{
    protected $signature = 'afterlife:check-inactivity';
    protected $description = 'Monitor active recoveries and send warnings if maintainers have been inactive past the threshold';

    public function handle(): int
    {
        $thresholdDays = (int) config('afterlife.inactivity_threshold_days', 60);
        $warningThreshold = now()->subDays($thresholdDays);

        $this->info("Scanning active recoveries past {$thresholdDays} days of inactivity...");

        /** @var \Illuminate\Database\Eloquent\Collection<int, Project> $inactiveProjects */
        $inactiveProjects = Project::with(['owner', 'originalOwner'])
            ->whereIn('status', [ProjectStatus::ADOPTED, ProjectStatus::UNDER_RECOVERY])
            ->where(function ($query) use ($warningThreshold) {
                $query->where('last_activity_at', '<', $warningThreshold)
                      ->orWhere(function ($q) use ($warningThreshold) {
                          $q->whereNull('last_activity_at')
                            ->where('updated_at', '<', $warningThreshold);
                      });
            })
            ->get();

        $count = $inactiveProjects->count();
        $this->info("Found {$count} project(s) with stalled recovery activity.");

        foreach ($inactiveProjects as $project) {
            $owner = $project->owner;

            NotificationService::send(
                $owner,
                'inactivity_warning',
                'Recovery Inactivity Warning',
                "Your adopted project '{$project->title}' has had no recorded activity for {$thresholdDays} days. Please update your roadmap or post a progress note.",
                route('user.recovery.workspace', $project)
            );

            NotificationService::notifyAdmins(
                'inactivity_alert',
                'Stalled Project Recovery Alert',
                "Project '{$project->title}' (Maintainer: {$owner->name}) has been inactive for over {$thresholdDays} days.",
                route('admin.projects.submissions.show', $project)
            );

            AuditService::log('INACTIVITY_WARNING_DISPATCHED', $project, [
                'owner_id' => $owner->id,
                'threshold_days' => $thresholdDays,
            ]);

            $this->line("Dispatched inactivity check for: {$project->title}");
        }

        $this->info("Inactivity scan completed successfully.");
        return Command::SUCCESS;
    }
}
