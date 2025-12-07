<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'view mosques', 'create mosques', 'edit mosques', 'delete mosques',
            'view housings', 'create housings', 'edit housings', 'delete housings',
            'view members', 'create members', 'edit members', 'delete members',
            'view neighborhoods', 'create neighborhoods', 'edit neighborhoods', 'delete neighborhoods',
            'view provinces', 'create provinces', 'edit provinces', 'delete provinces',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view professions', 'create professions', 'edit professions', 'delete professions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'data-entry']);
        $role1->givePermissionTo('view mosques');
        $role1->givePermissionTo('create mosques');
        $role1->givePermissionTo('edit mosques');
        $role1->givePermissionTo('view housings');
        $role1->givePermissionTo('create housings');
        $role1->givePermissionTo('edit housings');
        $role1->givePermissionTo('view members');
        $role1->givePermissionTo('create members');
        $role1->givePermissionTo('edit members');
        $role1->givePermissionTo('view neighborhoods');
        $role1->givePermissionTo('create neighborhoods');
        $role1->givePermissionTo('edit neighborhoods');
        $role1->givePermissionTo('view provinces');
        $role1->givePermissionTo('create provinces');
        $role1->givePermissionTo('edit provinces');
        $role1->givePermissionTo('view categories');
        $role1->givePermissionTo('create categories');
        $role1->givePermissionTo('edit categories');
        $role1->givePermissionTo('view professions');
        $role1->givePermissionTo('create professions');
        $role1->givePermissionTo('edit professions');

        $role2 = Role::create(['name' => 'reviewer']);
        $role2->givePermissionTo('view mosques');
        $role2->givePermissionTo('view housings');
        $role2->givePermissionTo('view members');
        $role2->givePermissionTo('view neighborhoods');
        $role2->givePermissionTo('view provinces');
        $role2->givePermissionTo('view categories');
        $role2->givePermissionTo('view professions');

        $role3 = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role3);

        $user = User::factory()->create([
            'name' => 'Data Entry User',
            'email' => 'entry@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role1);

        $user = User::factory()->create([
            'name' => 'Reviewer User',
            'email' => 'reviewer@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role2);
    }
}
