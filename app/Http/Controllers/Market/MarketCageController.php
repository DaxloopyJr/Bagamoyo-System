<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Models\Market\Market;
use App\Models\Market\MarketCage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MarketCageController extends Controller
{
    public function index()
    {
        $markets = Market::active()->get();
        return view('market.cages_index', compact('markets'));
    }

    public function getData(Request $request)
    {
        $query = MarketCage::with(['market']);

        if ($request->filled('market_id')) {
            $query->where('market_id', $request->market_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addColumn('market_name', function ($row) {
                return $row->market ? $row->market->name : 'N/A';
            })
            ->addColumn('cost_formatted', function ($row) {
                return number_format($row->cost, 2) . ' TZS';
            })
            ->addColumn('rent_cost_formatted', function ($row) {
                return number_format($row->rent_cost, 2) . ' TZS';
            })
            ->addColumn('status_badge', function ($row) {
                return $row->statusBadge;
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<button type="button" class="btn btn-warning edit-btn" data-id="' . $row->id . '" data-market="' . $row->market_id . '" data-number="' . e($row->cage_number) . '" data-cost="' . $row->cost . '" data-rent="' . $row->rent_cost . '" data-status="' . $row->status . '" data-occupied-by="' . e($row->occupied_by) . '" data-description="' . e($row->description) . '" title="Edit"><i class="bi bi-pencil"></i></button>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function create()
    {
        $markets = Market::active()->get();
        return view('market.cages_create', compact('markets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'cage_number' => 'required|string|max:50',
            'cost' => 'required|numeric|min:0',
            'rent_cost' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'occupied_by' => 'nullable|string|max:255',
            'occupied_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Check unique cage number within market
        $exists = MarketCage::where('market_id', $validated['market_id'])
            ->where('cage_number', $validated['cage_number'])
            ->exists();

        if ($exists) {
            return $this->errorResponse('Cage number already exists in this market.');
        }

        $cage = MarketCage::create($validated);

        // Update market cage counts
        $market = Market::find($validated['market_id']);
        $market->increment('total_cages');
        if ($validated['status'] === 'occupied') {
            $market->increment('occupied_cages');
        }

        $this->logActivity("Created cage {$cage->cage_number} in market {$market->name}", $cage, 'created');

        return $this->successResponse('Market cage created successfully.');
    }

    public function edit(MarketCage $cage)
    {
        return response()->json($cage->load('market'));
    }

    public function update(Request $request, MarketCage $cage)
    {
        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'cage_number' => 'required|string|max:50',
            'cost' => 'required|numeric|min:0',
            'rent_cost' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'occupied_by' => 'nullable|string|max:255',
            'occupied_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $oldStatus = $cage->status;
        $oldMarketId = $cage->market_id;

        $cage->update($validated);

        // Update market counts if status changed
        if ($oldStatus !== $validated['status'] || $oldMarketId != $validated['market_id']) {
            // Recalculate for old market
            if ($oldMarketId) {
                $oldMarket = Market::find($oldMarketId);
                $oldMarket->total_cages = MarketCage::where('market_id', $oldMarketId)->count();
                $oldMarket->occupied_cages = MarketCage::where('market_id', $oldMarketId)->where('status', 'occupied')->count();
                $oldMarket->save();
            }

            // Recalculate for new market
            $newMarket = Market::find($validated['market_id']);
            $newMarket->total_cages = MarketCage::where('market_id', $validated['market_id'])->count();
            $newMarket->occupied_cages = MarketCage::where('market_id', $validated['market_id'])->where('status', 'occupied')->count();
            $newMarket->save();
        }

        $this->logActivity("Updated cage {$cage->cage_number}", $cage, 'updated');

        return $this->successResponse('Market cage updated successfully.');
    }

    public function destroy(MarketCage $cage)
    {
        $marketId = $cage->market_id;
        $cage->delete();

        // Update market counts
        $market = Market::find($marketId);
        if ($market) {
            $market->total_cages = MarketCage::where('market_id', $marketId)->count();
            $market->occupied_cages = MarketCage::where('market_id', $marketId)->where('status', 'occupied')->count();
            $market->save();
        }

        $this->logActivity("Deleted cage {$cage->cage_number}", $cage, 'deleted');

        return $this->successResponse('Market cage deleted successfully.');
    }
}
