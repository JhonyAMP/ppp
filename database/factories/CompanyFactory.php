<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CompanyFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'razon_social' => fake()->unique()->word(10),
            'ruc' => fake()->unique()->numberBetween(10000000000, 99999999999),
            'direccion_fiscal' => fake()->streetAddress(),
            'responsable' =>  fake()->firstName() . ' ' . fake()->lastName() . ' ' . fake()->lastName(),
        ];
    }
}
