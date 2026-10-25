<?php

namespace Database\Factories;

use App\Enums\LineRecipientSource;
use App\Models\LineRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LineRecipient>
 */
class LineRecipientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source' => LineRecipientSource::User,
            'line_id' => 'U'.fake()->unique()->numerify('################'),
        ];
    }

    public function group(): static
    {
        return $this->state(fn (): array => [
            'source' => LineRecipientSource::Group,
            'line_id' => 'C'.fake()->unique()->numerify('################'),
        ]);
    }
}
