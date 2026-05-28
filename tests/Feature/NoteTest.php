<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Note;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_view_notes()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        Note::factory()->count(3)->create();

        $response = $this->getJson('/api/notes');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_admin_can_create_note()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        $client = Client::factory()->create();
        $noteData = [
            'client_id' => $client->id,
            'note' => 'This is a test note.',
        ];

        $response = $this->postJson('/api/notes', $noteData);

        $response->assertStatus(201)
            ->assertJsonFragment(['note' => 'This is a test note.']);

        $this->assertDatabaseHas('notes', $noteData);
    }

    public function test_admin_can_update_note()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        $note = Note::factory()->create();
        $updateData = ['note' => 'Updated note content.'];

        $response = $this->putJson("/api/notes/{$note->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'note' => 'Updated note content.',
        ]);
    }

    public function test_admin_can_delete_note()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        $note = Note::factory()->create();

        $response = $this->deleteJson("/api/notes/{$note->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}
