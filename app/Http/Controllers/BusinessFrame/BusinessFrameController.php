<?php

namespace App\Http\Controllers\BusinessFrame;

use App\Http\Controllers\Controller;
use App\Models\BusinessFrame\BusinessFrame;
use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BusinessFrameController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('frames.index', compact('regions'));
    }

    public function getData(Request $request)
    {
        $query = BusinessFrame::with(['region', 'district', 'ward', 'village']);

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('frame_name', 'like', "%{$search}%")
                    ->orWhere('frame_number', 'like', "%{$search}%")
                    ->orWhere('rented_to', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('location', function ($row) {
                $parts = array_filter([
                    $row->village ? $row->village->village : null,
                    $row->ward ? $row->ward->ward : null,
                    $row->district ? $row->district->district : null,
                ]);
                return implode(', ', $parts) ?: 'N/A';
            })
            ->addColumn('rent_cost_formatted', function ($row) {
                return number_format($row->rent_cost, 2) . ' TZS';
            })
            ->addColumn('status_badge', function ($row) {
                return $row->statusBadge;
            })
            ->addColumn('rent_period', function ($row) {
                if ($row->rent_start_date && $row->rent_end_date) {
                    return $row->rent_start_date->format('d M Y') . ' - ' . $row->rent_end_date->format('d M Y');
                }
                return 'N/A';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<a href="' . route('business-frames.show', $row) . '" class="btn btn-info" title="View"><i class="bi bi-eye"></i></a>';
                $buttons .= '<a href="' . route('business-frames.edit', $row) . '" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function create()
    {
        $regions = Region::all();
        return view('frames.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'frame_number' => 'required|string|max:50',
            'frame_name' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'village_id' => 'nullable|exists:villages,id',
            'street' => 'nullable|string|max:255',
            'area_description' => 'nullable|string',
            'status' => 'required|in:rented,not_rented,under_maintenance',
            'rent_cost' => 'required|numeric|min:0',
            'rented_to' => 'nullable|string|max:255',
            'rented_to_phone' => 'nullable|string|max:20',
            'rent_start_date' => 'nullable|date',
            'rent_end_date' => 'nullable|date|after_or_equal:rent_start_date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $frame = BusinessFrame::create($validated);
        $this->logActivity("Created business frame {$frame->frame_number}", $frame, 'created');

        return $this->successResponse('Business frame created successfully.', route('business-frames.index'));
    }

    public function show(BusinessFrame $frame)
    {
        $frame->load(['region', 'district', 'ward', 'village']);
        return view('frames.show', compact('frame'));
    }

    public function edit(BusinessFrame $frame)
    {
        $regions = Region::all();
        $districts = $frame->region_id ? District::where('region_id', $frame->region_id)->get() : [];
        $wards = $frame->district_id ? Ward::where('district_id', $frame->district_id)->get() : [];
        $villages = $frame->ward_id ? Village::where('ward_id', $frame->ward_id)->get() : [];

        return view('frames.edit', compact('frame', 'regions', 'districts', 'wards', 'villages'));
    }

    public function update(Request $request, BusinessFrame $frame)
    {
        $validated = $request->validate([
            'frame_number' => 'required|string|max:50',
            'frame_name' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'village_id' => 'nullable|exists:villages,id',
            'street' => 'nullable|string|max:255',
            'area_description' => 'nullable|string',
            'status' => 'required|in:rented,not_rented,under_maintenance',
            'rent_cost' => 'required|numeric|min:0',
            'rented_to' => 'nullable|string|max:255',
            'rented_to_phone' => 'nullable|string|max:20',
            'rent_start_date' => 'nullable|date',
            'rent_end_date' => 'nullable|date|after_or_equal:rent_start_date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $frame->update($validated);
        $this->logActivity("Updated business frame {$frame->frame_number}", $frame, 'updated');

        return $this->successResponse('Business frame updated successfully.', route('business-frames.index'));
    }

    public function destroy(BusinessFrame $frame)
    {
        $frame->delete();
        $this->logActivity("Deleted business frame {$frame->frame_number}", $frame, 'deleted');

        return $this->successResponse('Business frame deleted successfully.');
    }
}
