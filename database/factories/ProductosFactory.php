<?php

namespace Database\Factories;

use App\Models\Categorias;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Productos>
 */
class ProductosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categoria_id' => Categorias::inRandomOrder()->first()->id,
            'nombre' => fake()->word,
            'codigo' => fake()->unique()->bothify('???-#####'),
            'imagen' => fake()->imageUrl(640, 480, 'products', true, 'Faker'),
            'precio_venta' => fake()->randomFloat(2, 1, 1000),
            'estado' => fake()->boolean,
        ];
    }
}
