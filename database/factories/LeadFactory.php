<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['new', 'contacted', 'qualified', 'converted', 'lost', 'do_not_call'];
        $sources = ['website', 'phone', 'email', 'referral', 'walk_in', 'social_media', 'other'];

        return [
            'assigned_to_id' => User::inRandomOrder()->value('id') ?? User::factory()->create()->id,
            'created_by_id' => User::inRandomOrder()->value('id') ?? User::factory()->create()->id,
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional()->email(),
            'company' => fake()->optional()->company(),
            'status' => fake()->randomElement($statuses),
            'source' => fake()->randomElement($sources),
            'value' => fake()->optional()->randomFloat(2, 100, 10000),
            'notes' => fake()->optional()->sentence(),
            'follow_up_at' => fake()->optional()->dateTimeBetween('+1 day', '+30 days'),
        ];
    }
}
