<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'file_name' => $this->faker->word() . '.pdf',
            'file_path' => 'attachments/' . $this->faker->uuid() . '.pdf',
            'file_type' => 'application/pdf',
        ];
    }
}
