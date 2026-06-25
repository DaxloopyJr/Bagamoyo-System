<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use App\Models\Market\Market;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MarketController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('market.index', compact('regions'));
    }

    public function getData(Request $request)
    {
        $query = Market::with(['region', 'district', 'ward', 'village', 'cages']);

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where('name', 'like', "%{$search}%");
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
            ->addColumn('cages_summary', function ($row) {
                $total = $row->cages->count();
                $occupied = $row->cages->where('status', 'occupied')->count();
                $available = $total - $occupied;
                return "<span class='badge bg-success'>{$available} Available</span> <span class='badge bg-danger'>{$occupied} Occupied</span> <span class='badge bg-info'>{$total} Total</span>";
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<a href="' . route('markets.show', $row) . '" class="btn btn-info" title="View"><i class="bi bi-eye"></i></a>';
                $buttons .= '<a href="' . route('markets.edit', $row) . '" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['cages_summary', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $regions = Region::all();
        return view('market.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'village_id' => 'nullable|exists:villages,id',
            'street' => 'nullable|string|max:255',
            'total_cages' => 'nullable|integer|min:0',
            'market_type' => 'nullable|string|max:100',
            'facilities' => 'nullable',
        ]);

        if (!empty($validated['facilities']) && is_string($validated['facilities'])) {
            $validated['facilities'] = explode(',', $validated['facilities']);
        }

        $market = Market::create($validated);
        $this->logActivity("Created market {$market->name}", $market, 'created');

        return $this->successResponse('Market created successfully.', route('markets.index'));
    }

    public function show(Market $market)
    {
        $market->load(['region', 'district', 'ward', 'village', 'cages']);
        return view('market.show', compact('market'));
    }

    public function edit(Market $market)
    {
        $regions = Region::all();
        $districts = $market->region_id ? District::where('region_id', $market->region_id)->get() : [];
        $wards = $market->district_id ? Ward::where('district_id', $market->district_id)->get() : [];
        $villages = $market->ward_id ? Village::where('ward_id', $market->ward_id)->get() : [];

        return view('market.edit', compact('market', 'regions', 'districts', 'wards', 'villages'));
    }

    public function update(Request $request, Market $market)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'village_id' => 'nullable|exists:villages,id',
            'street' => 'nullable|string|max:255',
            'total_cages' => 'nullable|integer|min:0',
            'market_type' => 'nullable|string|max:100',
            'facilities' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if (!empty($validated['facilities']) && is_string($validated['facilities'])) {
            $validated['facilities'] = explode(',', $validated['facilities']);
        }

        $market->update($validated);
        $this->logActivity("Updated market {$market->name}", $market, 'updated');

        return $this->successResponse('Market updated successfully.', route('markets.index'));
    }

    public function destroy(Market $market)
    {
        $market->delete();
        $this->logActivity("Deleted market {$market->name}", $market, 'deleted');

        return $this->successResponse('Market deleted successfully.');
    }
}
