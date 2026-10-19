<?php

namespace Tests\Unit;

use App\Models\Project;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProjectProgressTest extends TestCase
{
    public function test_timeline_progress_is_zero_before_the_project_starts(): void
    {
        $project = new Project([
            'start_date' => '2026-10-20',
            'target_end_date' => '2026-11-20',
        ]);

        $this->assertSame(0.0, $project->timelineProgressPercentage(Carbon::parse('2026-10-15')));
    }

    public function test_timeline_progress_tracks_elapsed_project_time_and_is_capped_at_completion(): void
    {
        $project = new Project([
            'start_date' => '2026-10-01',
            'target_end_date' => '2026-10-31',
        ]);

        $this->assertSame(50.0, $project->timelineProgressPercentage(Carbon::parse('2026-10-16')));
        $this->assertSame(100.0, $project->timelineProgressPercentage(Carbon::parse('2026-11-01')));
    }

    public function test_timeline_progress_is_zero_when_project_dates_are_missing(): void
    {
        $project = new Project();

        $this->assertSame(0.0, $project->timelineProgressPercentage(Carbon::parse('2026-10-15')));
    }
}
