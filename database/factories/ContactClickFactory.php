<?php

namespace Database\Factories;

use App\Enums\ContactClickChannel;
use App\Models\ContactClick;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactClick>
 */
class ContactClickFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'channel' => fake()->randomElement(ContactClickChannel::cases()),
            'page_url' => 'http://localhost/',
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'line_notified_at' => null,
        ];
    }

    public function notified(): static
    {
        return $this->state(fn (): array => [
            'line_notified_at' => now(),
        ]);
    }
}
