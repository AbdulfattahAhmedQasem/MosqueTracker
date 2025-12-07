<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mosque;
use App\Models\Neighborhood;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesAndPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        
        // Create necessary data
        $province = Province::create(['name' => 'Test Province']);
        $neighborhood = Neighborhood::create(['name' => 'Test Neighborhood', 'province_id' => $province->id]);
        Mosque::create(['name' => 'Test Mosque', 'type' => 'جامع', 'neighborhood_id' => $neighborhood->id]);
    }

    public function test_super_admin_can_access_everything()
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $this->actingAs($user)
            ->get(route('mosques.index'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->get(route('mosques.create'))
            ->assertStatus(200);

        $mosque = Mosque::first();
        $this->actingAs($user)
            ->delete(route('mosques.destroy', $mosque))
            ->assertRedirect(route('mosques.index'));
            
        $this->assertDatabaseMissing('mosques', ['id' => $mosque->id]);
    }

    public function test_data_entry_can_create_and_edit_but_not_delete()
    {
        $user = User::factory()->create();
        $user->assignRole('data-entry');

        // Can view
        $this->actingAs($user)
            ->get(route('mosques.index'))
            ->assertStatus(200);

        // Can create
        $this->actingAs($user)
            ->get(route('mosques.create'))
            ->assertStatus(200);

        $province = Province::first();
        $neighborhood = Neighborhood::first();
        
        $response = $this->actingAs($user)
            ->post(route('mosques.store'), [
                'name' => 'New Mosque',
                'type' => 'مسجد',
                'neighborhood_id' => $neighborhood->id,
            ]);
            
        $response->assertRedirect(route('mosques.index'));
        $this->assertDatabaseHas('mosques', ['name' => 'New Mosque']);

        // Cannot delete
        $mosque = Mosque::first();
        $this->actingAs($user)
            ->delete(route('mosques.destroy', $mosque))
            ->assertStatus(403); // Forbidden
            
        $this->assertDatabaseHas('mosques', ['id' => $mosque->id]);
    }

    public function test_reviewer_can_only_view()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        // Can view
        $this->actingAs($user)
            ->get(route('mosques.index'))
            ->assertStatus(200);

        // Cannot create
        $this->actingAs($user)
            ->get(route('mosques.create'))
            ->assertStatus(403);

        // Cannot edit
        $mosque = Mosque::first();
        $this->actingAs($user)
            ->get(route('mosques.edit', $mosque))
            ->assertStatus(403);

        // Cannot delete
        $this->actingAs($user)
            ->delete(route('mosques.destroy', $mosque))
            ->assertStatus(403);
    }
}
