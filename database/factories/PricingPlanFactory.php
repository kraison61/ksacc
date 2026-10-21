<?php

namespace Database\Factories;

use App\Models\PricingPlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PricingPlan>
 */
class PricingPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'slug' => Str::slug($title),
            'type' => PricingPlan::TYPE_PACKAGE,
            'title' => $title,
            'subtitle' => fake()->sentence(4),
            'price_prefix' => 'เริ่มต้น',
            'price_amount' => (string) fake()->numberBetween(1500, 12000),
            'price_suffix' => 'บาท/เดือน',
            'features' => [
                fake()->sentence(3),
                fake()->sentence(3),
                fake()->sentence(3),
            ],
            'cta_label' => 'สอบถามราคา',
            'is_featured' => false,
            'sort_order' => fake()->numberBetween(0, 100),
            'is_published' => true,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_featured' => true,
        ]);
    }

    public function aLaCarte(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => PricingPlan::TYPE_A_LA_CARTE,
            'subtitle' => null,
            'features' => null,
            'cta_label' => null,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => false,
        ]);
    }
}
