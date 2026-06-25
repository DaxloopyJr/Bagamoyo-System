<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permissions.index');
    }

    public function getData(Request $request)
    {
        $query = Permission::withCount('roles');

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<button type="button" class="btn btn-warning edit-btn" data-id="' . $row->id . '" data-name="' . e($row->name) . '" title="Edit"><i class="bi bi-pencil"></i></button>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $this->logActivity("Created permission {$permission->name}", null, 'permission_created');

        return $this->successResponse('Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return response()->json($permission);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $validated['name']]);
        $this->logActivity("Updated permission {$permission->name}", null, 'permission_updated');

        return $this->successResponse('Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return $this->errorResponse('Cannot delete permission assigned to roles.');
        }

        $permission->delete();
        $this->logActivity("Deleted permission {$permission->name}", null, 'permission_deleted');

        return $this->successResponse('Permission deleted successfully.');
    }
}
