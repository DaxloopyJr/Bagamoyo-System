<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use App\Models\License\LicenseCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LicenseCategoryController extends Controller
{
    public function index()
    {
        return view('admin.business_settings.license_categories');
    }

    public function getData(Request $request)
    {
        $query = LicenseCategory::withCount('licenses');

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('status_badge', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('fee_formatted', function ($row) {
                return number_format($row->default_fee, 2) . ' TZS';
            })
            ->addColumn('licenses_count', function ($row) {
                $count = $row->licenses_count;
                return $count > 0
                    ? '<a href="' . route('licenses.index') . '?category_id=' . $row->id . '" class="badge bg-info text-decoration-none">' . $count . ' license' . ($count > 1 ? 's' : '') . '</a>'
                    : '<span class="badge bg-secondary">0</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<button type="button" class="btn btn-warning edit-btn" data-id="' . $row->id . '" title="Edit"><i class="bi bi-pencil"></i></button>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status_badge', 'licenses_count', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:license_categories,name',
            'code' => 'nullable|string|max:50|unique:license_categories,code',
            'description' => 'nullable|string',
            'default_fee' => 'required|numeric|min:0',
        ]);

        $category = LicenseCategory::create($validated);
        $this->logActivity("Created license category {$category->name}", $category, 'created');

        return $this->successResponse('License category created successfully.');
    }

    public function edit(LicenseCategory $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, LicenseCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:license_categories,name,' . $category->id,
            'code' => 'nullable|string|max:50|unique:license_categories,code,' . $category->id,
            'description' => 'nullable|string',
            'default_fee' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $category->update($validated);
        $this->logActivity("Updated license category {$category->name}", $category, 'updated');

        return $this->successResponse('License category updated successfully.');
    }

    public function destroy(LicenseCategory $category)
    {
        if ($category->licenses()->count() > 0) {
            return $this->errorResponse('Cannot delete category that has associated licenses.');
        }

        $category->delete();
        $this->logActivity("Deleted license category {$category->name}", $category, 'deleted');

        return $this->successResponse('License category deleted successfully.');
    }
}
