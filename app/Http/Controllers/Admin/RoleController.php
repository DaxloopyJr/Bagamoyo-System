<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index');
    }

    public function getData(Request $request)
    {
        $query = Role::with('permissions')->withCount('users');

        return DataTables::of($query)
            ->addColumn('permissions_count', function ($row) {
                return $row->permissions->count();
            })
            ->addColumn('action', function ($row) {
                if (in_array($row->name, ['Super Admin', 'Admin'])) {
                    return '<span class="badge bg-info">System Role</span>';
                }
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<a href="' . route('admin.roles.edit', $row) . '" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($p) {
            $parts = explode('_', $p->name);
            return $parts[0] ?? 'general';
        });
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

        if (!empty($validated['permissions'])) {
            $permissionNames = Permission::whereIn('id', $validated['permissions'])->pluck('name');
            $role->syncPermissions($permissionNames);
        }

        $this->logActivity("Created role {$role->name}", null, 'role_created');

        return $this->successResponse('Role created successfully.', route('admin.roles.index'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($p) {
            $parts = explode('_', $p->name);
            return $parts[0] ?? 'general';
        });
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if (in_array($role->name, ['Super Admin', 'Admin'])) {
            return $this->errorResponse('System roles cannot be modified.');
        }

        $role->update(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $permissionNames = Permission::whereIn('id', $validated['permissions'])->pluck('name');
            $role->syncPermissions($permissionNames);
        } else {
            $role->syncPermissions([]);
        }

        $this->logActivity("Updated role {$role->name}", null, 'role_updated');

        return $this->successResponse('Role updated successfully.', route('admin.roles.index'));
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Super Admin', 'Admin'])) {
            return $this->errorResponse('System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return $this->errorResponse('Cannot delete role that has assigned users.');
        }

        $role->delete();
        $this->logActivity("Deleted role {$role->name}", null, 'role_deleted');

        return $this->successResponse('Role deleted successfully.');
    }
}
