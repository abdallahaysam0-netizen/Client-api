<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Attachment;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Storage::fake('public');
    }

    public function test_admin_can_view_attachments()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        Attachment::factory()->count(3)->create();

        $response = $this->getJson('/api/attachments');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_admin_can_upload_attachment()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        $client = Client::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->postJson('/api/attachments', [
            'client_id' => $client->id,
            'file' => $file,
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('attachments', [
            'client_id' => $client->id,
            'file_name' => 'document.pdf',
        ]);
    }

    public function test_admin_can_delete_attachment()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        $attachment = Attachment::factory()->create();

        $response = $this->deleteJson("/api/attachments/{$attachment->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('attachments', ['id' => $attachment->id]);
    }
}
