<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.users.index', compact('roles'));
    }

    public function getData(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('role_id')) {
            $query->role($request->role_id);
        }
        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('roles_list', function ($row) {
                return $row->roles->pluck('name')->implode(', ') ?: 'No Role';
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<a href="' . route('admin.users.edit', $row) . '" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-' . ($row->is_active ? 'secondary' : 'success') . ' toggle-status-btn" data-id="' . $row->id . '" title="' . ($row->is_active ? 'Deactivate' : 'Activate') . '"><i class="bi bi-toggle-' . ($row->is_active ? 'on' : 'off') . '"></i></button>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        $roleNames = Role::whereIn('id', $validated['roles'])->pluck('name');
        $user->syncRoles($roleNames);

        $this->logActivity("Created user {$user->name}", $user, 'created');

        return $this->successResponse('User created successfully.', route('admin.users.index'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        $roleNames = Role::whereIn('id', $validated['roles'])->pluck('name');
        $user->syncRoles($roleNames);

        $this->logActivity("Updated user {$user->name}", $user, 'updated');

        return $this->successResponse('User updated successfully.', route('admin.users.index'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return $this->errorResponse('You cannot delete your own account.');
        }

        $user->delete();
        $this->logActivity("Deleted user {$user->name}", $user, 'deleted');

        return $this->successResponse('User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return $this->errorResponse('You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        $this->logActivity("{$status} user {$user->name}", $user, 'status_changed');

        return $this->successResponse("User {$status} successfully.");
    }
}
