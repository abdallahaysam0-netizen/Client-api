<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'note' => $this->faker->paragraph(),
        ];
    }
}
