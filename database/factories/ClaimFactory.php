<?php

namespace Database\Factories;

use App\Enums\ClaimStatus;
use App\Models\Claim;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Claim>
 */
class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    public function definition(): array
    {
        return [
            'report_id' => Report::factory()->found(),
            'claimant_id' => User::factory(),
            'status' => ClaimStatus::Submitted,
            'submitted_at' => now(),
        ];
    }

    public function forReport(Report $report): static
    {
        return $this->state(fn () => ['report_id' => $report->id]);
    }

    public function by(User $user): static
    {
        return $this->state(fn () => ['claimant_id' => $user->id]);
    }

    public function inHandover(): static
    {
        return $this->state(fn () => ['status' => ClaimStatus::InHandover]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => ClaimStatus::Completed,
            'resolved_at' => now(),
        ]);
    }

    public function submittedHoursAgo(int $hours): static
    {
        return $this->state(fn () => ['submitted_at' => now()->subHours($hours)]);
    }
}
