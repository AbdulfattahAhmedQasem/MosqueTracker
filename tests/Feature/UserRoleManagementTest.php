<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_super_admin_can_manage_users()
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        // Can view users list
        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertStatus(200);

        // Can view create form
        $this->actingAs($admin)
            ->get(route('users.create'))
            ->assertStatus(200);

        // Can create user
        $response = $this->actingAs($admin)
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'new@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'roles' => ['data-entry'],
            ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
        $this->assertTrue(User::where('email', 'new@example.com')->first()->hasRole('data-entry'));

        $user = User::where('email', 'new@example.com')->first();

        // Can edit user
        $this->actingAs($admin)
            ->get(route('users.edit', $user))
            ->assertStatus(200);

        $response = $this->actingAs($admin)
            ->put(route('users.update', $user), [
                'name' => 'Updated User',
                'email' => 'new@example.com',
                'roles' => ['reviewer'],
            ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['name' => 'Updated User']);
        $this->assertTrue($user->fresh()->hasRole('reviewer'));

        // Can delete user
        $response = $this->actingAs($admin)
            ->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_super_admin_can_manage_roles()
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        // Can view roles list
        $this->actingAs($admin)
            ->get(route('roles.index'))
            ->assertStatus(200);

        // Can view create form
        $this->actingAs($admin)
            ->get(route('roles.create'))
            ->assertStatus(200);

        // Can create role
        $response = $this->actingAs($admin)
            ->post(route('roles.store'), [
                'name' => 'new-role',
                'permissions' => ['view mosques', 'create mosques'],
            ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'new-role']);
        $role = Role::findByName('new-role');
        $this->assertTrue($role->hasPermissionTo('view mosques'));
        $this->assertTrue($role->hasPermissionTo('create mosques'));

        // Can edit role
        $this->actingAs($admin)
            ->get(route('roles.edit', $role))
            ->assertStatus(200);

        $response = $this->actingAs($admin)
            ->put(route('roles.update', $role), [
                'name' => 'updated-role',
                'permissions' => ['view mosques'],
            ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'updated-role']);
        $role = Role::findByName('updated-role');
        $this->assertTrue($role->hasPermissionTo('view mosques'));
        $this->assertFalse($role->hasPermissionTo('create mosques'));

        // Can delete role
        $response = $this->actingAs($admin)
            ->delete(route('roles.destroy', $role));

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_non_admin_cannot_manage_users_or_roles()
    {
        $user = User::factory()->create();
        $user->assignRole('data-entry');

        // Users
        $this->actingAs($user)->get(route('users.index'))->assertStatus(403);
        $this->actingAs($user)->get(route('users.create'))->assertStatus(403);
        $this->actingAs($user)->post(route('users.store'), [])->assertStatus(403);
        
        $targetUser = User::factory()->create();
        $this->actingAs($user)->get(route('users.edit', $targetUser))->assertStatus(403);
        $this->actingAs($user)->put(route('users.update', $targetUser), [])->assertStatus(403);
        $this->actingAs($user)->delete(route('users.destroy', $targetUser))->assertStatus(403);

        // Roles
        $this->actingAs($user)->get(route('roles.index'))->assertStatus(403);
        $this->actingAs($user)->get(route('roles.create'))->assertStatus(403);
        $this->actingAs($user)->post(route('roles.store'), [])->assertStatus(403);
        
        $role = Role::first();
        $this->actingAs($user)->get(route('roles.edit', $role))->assertStatus(403);
        $this->actingAs($user)->put(route('roles.update', $role), [])->assertStatus(403);
        $this->actingAs($user)->delete(route('roles.destroy', $role))->assertStatus(403);
    }
}
