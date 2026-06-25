<?php

namespace App\Http\Controllers\Fishery;

use App\Http\Controllers\Controller;
use App\Models\Fishery\Fisherman;
use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FishermanController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('fishery.index', compact('regions'));
    }

    public function getData(Request $request)
    {
        $query = Fisherman::with(['region', 'district', 'ward', 'village', 'boats']);

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('location', function ($row) {
                $parts = array_filter([
                    $row->village ? $row->village->village : null,
                    $row->ward ? $row->ward->ward : null,
                ]);
                return implode(', ', $parts) ?: 'N/A';
            })
            ->addColumn('boats_count', function ($row) {
                return $row->boats->count();
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<a href="' . route('fishermen.show', $row) . '" class="btn btn-info" title="View"><i class="bi bi-eye"></i></a>';
                $buttons .= '<a href="' . route('fishermen.edit', $row) . '" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $regions = Region::all();
        return view('fishery.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'id_number' => 'nullable|string|max:50',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'village_id' => 'nullable|exists:villages,id',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['registration_date'] = now();

        $fisherman = Fisherman::create($validated);
        $this->logActivity("Registered fisherman {$fisherman->name}", $fisherman, 'created');

        return $this->successResponse('Fisherman registered successfully.', route('fishermen.index'));
    }

    public function show(Fisherman $fisherman)
    {
        $fisherman->load(['region', 'district', 'ward', 'village', 'boats']);
        return view('fishery.show', compact('fisherman'));
    }

    public function edit(Fisherman $fisherman)
    {
        $regions = Region::all();
        $districts = $fisherman->region_id ? District::where('region_id', $fisherman->region_id)->get() : [];
        $wards = $fisherman->district_id ? Ward::where('district_id', $fisherman->district_id)->get() : [];
        $villages = $fisherman->ward_id ? Village::where('ward_id', $fisherman->ward_id)->get() : [];

        return view('fishery.edit', compact('fisherman', 'regions', 'districts', 'wards', 'villages'));
    }

    public function update(Request $request, Fisherman $fisherman)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'id_number' => 'nullable|string|max:50',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'village_id' => 'nullable|exists:villages,id',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $fisherman->update($validated);
        $this->logActivity("Updated fisherman {$fisherman->name}", $fisherman, 'updated');

        return $this->successResponse('Fisherman updated successfully.', route('fishermen.index'));
    }

    public function destroy(Fisherman $fisherman)
    {
        $fisherman->delete();
        $this->logActivity("Deleted fisherman {$fisherman->name}", $fisherman, 'deleted');

        return $this->successResponse('Fisherman deleted successfully.');
    }
}
