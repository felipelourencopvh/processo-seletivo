<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Processo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProcessoFactory extends Factory
{
    protected $model = Processo::class;

    public function definition(): array
    {
        return [
            'titulo' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'descricao' => $this->faker->word(),
            'data_abertura' => Carbon::now(),
            'data_resultado' => Carbon::now(),
            'data_suspensao_parcial' => Carbon::now(),
            'data_suspensao_integral' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'secretaria_id' => 1,
        ];
    }
}
