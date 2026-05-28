<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_view_clients()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_admin_can_create_client()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        $clientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/clients', $clientData);

        $response->assertStatus(201)
            ->assertJsonFragment($clientData);

        $this->assertDatabaseHas('clients', $clientData);
    }

    public function test_manager_cannot_create_client()
    {
        $manager = User::factory()->create();
        $manager->assignRole(\Spatie\Permission\Models\Role::findByName('Manager', 'api'));
        Passport::actingAs($manager, [], 'api');

        $clientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/clients', $clientData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('clients', ['email' => 'john@example.com']);
    }

    public function test_unauthenticated_user_cannot_access_clients()
    {
        $response = $this->getJson('/api/clients');

        $response->assertStatus(401);
    }
}
