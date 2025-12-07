<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:super-admin'),
        ];
    }

    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // ترتيب الصلاحيات حسب النوع والعمليات
        $permissions = Permission::all();
        
        // ترتيب مخصص: النوع أولاً، ثم العملية (create, edit, delete, view)
        $orderedPermissions = $permissions->sortBy(function ($permission) {
            $order = [
                // المنسوبين
                'create members' => 1,
                'edit members' => 2,
                'delete members' => 3,
                'view members' => 4,
                // المساجد
                'create mosques' => 5,
                'edit mosques' => 6,
                'delete mosques' => 7,
                'view mosques' => 8,
                // السكن
                'create housings' => 9,
                'edit housings' => 10,
                'delete housings' => 11,
                'view housings' => 12,
                // الفئات
                'create categories' => 13,
                'edit categories' => 14,
                'delete categories' => 15,
                'view categories' => 16,
                // المهن
                'create professions' => 17,
                'edit professions' => 18,
                'delete professions' => 19,
                'view professions' => 20,
                // الأحياء
                'create neighborhoods' => 21,
                'edit neighborhoods' => 22,
                'delete neighborhoods' => 23,
                'view neighborhoods' => 24,
                // المحافظات
                'create provinces' => 25,
                'edit provinces' => 26,
                'delete provinces' => 27,
                'view provinces' => 28,
            ];
            
            return $order[$permission->name] ?? 999;
        });
        
        return view('roles.create', ['permissions' => $orderedPermissions]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('roles.index')
            ->with('success', 'تم إضافة الدور بنجاح');
    }

    public function edit(Role $role)
    {
        // ترتيب الصلاحيات حسب النوع والعمليات
        $permissions = Permission::all();
        
        // ترتيب مخصص: النوع أولاً، ثم العملية (create, edit, delete, view)
        $orderedPermissions = $permissions->sortBy(function ($permission) {
            $order = [
                // المنسوبين
                'create members' => 1,
                'edit members' => 2,
                'delete members' => 3,
                'view members' => 4,
                // المساجد
                'create mosques' => 5,
                'edit mosques' => 6,
                'delete mosques' => 7,
                'view mosques' => 8,
                // السكن
                'create housings' => 9,
                'edit housings' => 10,
                'delete housings' => 11,
                'view housings' => 12,
                // الفئات
                'create categories' => 13,
                'edit categories' => 14,
                'delete categories' => 15,
                'view categories' => 16,
                // المهن
                'create professions' => 17,
                'edit professions' => 18,
                'delete professions' => 19,
                'view professions' => 20,
                // الأحياء
                'create neighborhoods' => 21,
                'edit neighborhoods' => 22,
                'delete neighborhoods' => 23,
                'view neighborhoods' => 24,
                // المحافظات
                'create provinces' => 25,
                'edit provinces' => 26,
                'delete provinces' => 27,
                'view provinces' => 28,
            ];
            
            return $order[$permission->name] ?? 999;
        });
        
        return view('roles.edit', ['role' => $role, 'permissions' => $orderedPermissions]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('roles.index')
            ->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return redirect()->route('roles.index')
                ->with('error', 'لا يمكن حذف دور المشرف العام');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'لا يمكن حذف هذا الدور لأنه مسند لمستخدمين');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'تم حذف الدور بنجاح');
    }
}
