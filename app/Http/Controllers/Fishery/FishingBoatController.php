<?php

namespace App\Http\Controllers\Fishery;

use App\Http\Controllers\Controller;
use App\Models\Fishery\Fisherman;
use App\Models\Fishery\FishingBoat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FishingBoatController extends Controller
{
    public function index()
    {
        $fishermen = Fisherman::active()->get();
        return view('fishery.boats_index', compact('fishermen'));
    }

    public function getData(Request $request)
    {
        $query = FishingBoat::with(['fisherman']);

        if ($request->filled('fisherman_id')) {
            $query->where('fisherman_id', $request->fisherman_id);
        }
        if ($request->filled('boat_type')) {
            $query->where('boat_type', $request->boat_type);
        }
        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('owner_name', 'like', "%{$search}%")
                    ->orWhere('boat_number', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('fisherman_name', function ($row) {
                return $row->fisherman ? $row->fisherman->name : 'N/A';
            })
            ->addColumn('capacity_formatted', function ($row) {
                return number_format($row->capacity_kg, 0) . ' kg';
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<a href="' . route('fishing-boats.edit', $row) . '" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $fishermen = Fisherman::active()->get();
        return view('fishery.boats_create', compact('fishermen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fisherman_id' => 'required|exists:fishermen,id',
            'owner_name' => 'required|string|max:255',
            'boat_number' => 'required|string|max:50|unique:fishing_boats,boat_number',
            'capacity_kg' => 'required|numeric|min:0',
            'length_m' => 'nullable|numeric|min:0',
            'boat_type' => 'nullable|string|max:100',
            'engine_power' => 'nullable|string|max:100',
            'year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'registration_status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $boat = FishingBoat::create($validated);
        $this->logActivity("Registered fishing boat {$boat->boat_number}", $boat, 'created');

        return $this->successResponse('Fishing boat registered successfully.', route('fishing-boats.index'));
    }

    public function edit(FishingBoat $boat)
    {
        $fishermen = Fisherman::active()->get();
        return view('fishery.boats_edit', compact('boat', 'fishermen'));
    }

    public function update(Request $request, FishingBoat $boat)
    {
        $validated = $request->validate([
            'fisherman_id' => 'required|exists:fishermen,id',
            'owner_name' => 'required|string|max:255',
            'boat_number' => 'required|string|max:50|unique:fishing_boats,boat_number,' . $boat->id,
            'capacity_kg' => 'required|numeric|min:0',
            'length_m' => 'nullable|numeric|min:0',
            'boat_type' => 'nullable|string|max:100',
            'engine_power' => 'nullable|string|max:100',
            'year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'registration_status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $boat->update($validated);
        $this->logActivity("Updated fishing boat {$boat->boat_number}", $boat, 'updated');

        return $this->successResponse('Fishing boat updated successfully.', route('fishing-boats.index'));
    }

    public function destroy(FishingBoat $boat)
    {
        $boat->delete();
        $this->logActivity("Deleted fishing boat {$boat->boat_number}", $boat, 'deleted');

        return $this->successResponse('Fishing boat deleted successfully.');
    }
}
