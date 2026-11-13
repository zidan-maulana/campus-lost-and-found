<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\ItemCategory;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => ItemCategory::factory(),
            'type' => ReportType::Found,
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(12),
            'campus_location' => 'Kampus Meruya',
            'location' => $this->faker->words(3, true),
            'incident_date' => now()->subDays(2)->toDateString(),
            'incident_time' => '10.00 WIB (Pagi)',
            'status' => ReportStatus::Open,
        ];
    }

    public function lost(): static
    {
        return $this->state(fn () => ['type' => ReportType::Lost]);
    }

    public function found(): static
    {
        return $this->state(fn () => ['type' => ReportType::Found]);
    }

    public function ownedBy(User $user): static
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['status' => ReportStatus::Hidden]);
    }

    public function resolved(): static
    {
        return $this->state(fn () => ['status' => ReportStatus::Resolved]);
    }
}
