<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    public function definition(): array
    {
        $status = ['menunggu', 'diproses', 'selesai', 'ditolak'];

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'location' => fake()->city(),
            'image' => null,
            'is_anonymous' => fake()->boolean(),
            'status' => fake()->randomElement($status),
        ];
    }

    /**
     * Status menunggu
     */
    public function waiting(): static
    {
        return $this->state(fn () => [
            'status' => 'menunggu',
        ]);
    }

    /**
     * Status diproses
     */
    public function processing(): static
    {
        return $this->state(fn () => [
            'status' => 'diproses',
        ]);
    }

    /**
     * Status selesai
     */
    public function done(): static
    {
        return $this->state(fn () => [
            'status' => 'selesai',
        ]);
    }

    /**
     * Status ditolak
     */
    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => 'ditolak',
        ]);
    }
}