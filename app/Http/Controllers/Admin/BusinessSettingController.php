<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License\LicenseCategory;
use App\Models\Settings\RevenueSource;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BusinessSettingController extends Controller
{
    public function index()
    {
        $categoriesCount = LicenseCategory::count();
        $revenueSourcesCount = RevenueSource::count();
        return view('admin.business_settings.index', compact('categoriesCount', 'revenueSourcesCount'));
    }

    public function revenueSources()
    {
        return view('admin.business_settings.revenue_sources');
    }

    public function revenueSourcesData(Request $request)
    {
        $query = RevenueSource::query();

        return DataTables::of($query)
            ->addColumn('status_badge', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<button type="button" class="btn btn-warning edit-btn" data-id="' . $row->id . '" data-name="' . e($row->name) . '" data-description="' . e($row->description) . '" data-type="' . e($row->type) . '" title="Edit"><i class="bi bi-pencil"></i></button>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function storeRevenueSource(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:license,market,frame_rent,fishery,other',
        ]);

        $source = RevenueSource::create($validated);
        $this->logActivity("Created revenue source {$source->name}", $source, 'created');

        return $this->successResponse('Revenue source created successfully.');
    }

    public function updateRevenueSource(Request $request, RevenueSource $source)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:license,market,frame_rent,fishery,other',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $source->update($validated);
        $this->logActivity("Updated revenue source {$source->name}", $source, 'updated');

        return $this->successResponse('Revenue source updated successfully.');
    }

    public function destroyRevenueSource(RevenueSource $source)
    {
        $source->delete();
        $this->logActivity("Deleted revenue source {$source->name}", $source, 'deleted');

        return $this->successResponse('Revenue source deleted successfully.');
    }
}
