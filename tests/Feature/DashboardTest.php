<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_view_dashboard_stats()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        $response = $this->getJson('/api/dashboard/stats');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_activities()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('Admin', 'api'));
        Passport::actingAs($admin, [], 'api');

        $response = $this->getJson('/api/activities');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_check_system_health()
    {
        $user = User::factory()->create();
        Passport::actingAs($user, [], 'api');

        $response = $this->getJson('/api/system/health');

        $response->assertStatus(200)
            ->assertJsonStructure(['stability', 'ramUsed', 'ramTotal', 'cpuLoad']);
    }

    public function test_authenticated_user_can_get_notifications()
    {
        $user = User::factory()->create();
        Passport::actingAs($user, [], 'api');

        $response = $this->getJson('/api/notifications');

        $response->assertStatus(200);
    }
}
