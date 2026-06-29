<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\BusinessFrame\BusinessFrame;
use App\Models\Fishery\Fisherman;
use App\Models\Fishery\FishingBoat;
use App\Models\License\BusinessLicense;
use App\Models\Market\Market;
use App\Models\Market\MarketCage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    // License Reports
    public function licenses()
    {
        $stats = [
            'total' => BusinessLicense::count(),
            'active' => BusinessLicense::where('is_active', true)->where('expiry_date', '>=', now())->count(),
            'expired' => BusinessLicense::where('expiry_date', '<', now())->count(),
            'expiring_soon' => BusinessLicense::whereBetween('expiry_date', [now(), now()->addDays(30)])->count(),
            'total_revenue' => BusinessLicense::where('payment_status', '!=', 'not_paid')->sum('payment_amount'),
        ];
        return view('reports.licenses', compact('stats'));
    }

    public function licensesData(Request $request)
    {
        $query = BusinessLicense::with(['category', 'region', 'district', 'ward', 'village']);

        if ($request->filled('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }
        if ($request->filled('category_id')) {
            $query->where('license_category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            if ($request->status === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($request->status === 'active') {
                $query->where('expiry_date', '>=', now());
            }
        }

        return DataTables::of($query)
            ->addColumn('category_name', fn($row) => $row->category ? $row->category->name : 'N/A')
            ->addColumn('location', function ($row) {
                $parts = array_filter([
                    $row->village ? $row->village->village : null,
                    $row->ward ? $row->ward->ward : null,
                ]);
                return implode(', ', $parts) ?: 'N/A';
            })
            ->addColumn('status', fn($row) => $row->statusBadge)
            ->addColumn('payment_status_badge', function ($row) {
                $badges = [
                    'issue_payment' => '<span class="badge bg-success">Paid</span>',
                    'renewal_payment' => '<span class="badge bg-info">Renewal</span>',
                    'not_paid' => '<span class="badge bg-danger">Not Paid</span>',
                ];
                return $badges[$row->payment_status] ?? '<span class="badge bg-secondary">Unknown</span>';
            })
            ->rawColumns(['status', 'payment_status_badge'])
            ->make(true);
    }

    // Expired Licenses Report
    public function expiredLicenses()
    {
        return view('reports.expired_licenses');
    }

    public function expiredLicensesData(Request $request)
    {
        $query = BusinessLicense::where('expiry_date', '<', now())
            ->with(['category', 'region', 'district', 'ward']);

        if ($request->filled('days_overdue')) {
            $query->where('expiry_date', '>=', now()->subDays($request->days_overdue));
        }

        return DataTables::of($query)
            ->addColumn('category_name', fn($row) => $row->category ? $row->category->name : 'N/A')
            ->addColumn('days_overdue', function ($row) {
                return '<span class="badge bg-danger">' . abs($row->daysUntilExpiry) . ' days</span>';
            })
            ->addColumn('location', function ($row) {
                $parts = array_filter([$row->ward ? $row->ward->ward : null, $row->district ? $row->district->district : null]);
                return implode(', ', $parts) ?: 'N/A';
            })
            ->rawColumns(['days_overdue'])
            ->make(true);
    }

    // Fishery Report
    public function fishery()
    {
        $stats = [
            'total_fishermen' => Fisherman::where('is_active', true)->count(),
            'total_boats' => FishingBoat::where('is_active', true)->count(),
            'total_capacity' => FishingBoat::where('is_active', true)->sum('capacity_kg'),
        ];
        return view('reports.fishery', compact('stats'));
    }

    public function fisheryData(Request $request)
    {
        $type = $request->get('report_type', 'fishermen');

        if ($type === 'fishermen') {
            $query = Fisherman::with(['region', 'district', 'ward', 'village', 'boats']);

            if ($request->filled('region_id')) {
                $query->where('region_id', $request->region_id);
            }

            return DataTables::of($query)
                ->addColumn('location', function ($row) {
                    $parts = array_filter([$row->village ? $row->village->village : null, $row->ward ? $row->ward->ward : null]);
                    return implode(', ', $parts) ?: 'N/A';
                })
                ->addColumn('boats_count', fn($row) => $row->boats->count())
                ->addColumn('total_capacity', function ($row) {
                    return number_format($row->boats->sum('capacity_kg'), 0) . ' kg';
                })
                ->make(true);
        } else {
            $query = FishingBoat::with(['fisherman']);

            if ($request->filled('boat_type')) {
                $query->where('boat_type', $request->boat_type);
            }

            return DataTables::of($query)
                ->addColumn('fisherman_name', fn($row) => $row->fisherman ? $row->fisherman->name : 'N/A')
                ->addColumn('capacity_formatted', fn($row) => number_format($row->capacity_kg, 0) . ' kg')
                ->make(true);
        }
    }

    // Markets Report
    public function markets()
    {
        $stats = [
            'total_markets' => Market::where('is_active', true)->count(),
            'total_cages' => MarketCage::count(),
            'occupied_cages' => MarketCage::where('status', 'occupied')->count(),
            'available_cages' => MarketCage::where('status', 'available')->count(),
        ];
        return view('reports.markets', compact('stats'));
    }

    public function marketsData(Request $request)
    {
        $type = $request->get('report_type', 'markets');

        if ($type === 'markets') {
            $query = Market::with(['region', 'district', 'ward', 'cages']);

            return DataTables::of($query)
                ->addColumn('location', function ($row) {
                    $parts = array_filter([$row->ward ? $row->ward->ward : null, $row->district ? $row->district->district : null]);
                    return implode(', ', $parts) ?: 'N/A';
                })
                ->addColumn('total_cages', fn($row) => $row->cages->count())
                ->addColumn('occupied_cages', fn($row) => $row->cages->where('status', 'occupied')->count())
                ->addColumn('available_cages', fn($row) => $row->cages->where('status', 'available')->count())
                ->make(true);
        } else {
            $query = MarketCage::with(['market']);

            if ($request->filled('market_id')) {
                $query->where('market_id', $request->market_id);
            }

            return DataTables::of($query)
                ->addColumn('market_name', fn($row) => $row->market ? $row->market->name : 'N/A')
                ->addColumn('status_badge', fn($row) => $row->statusBadge)
                ->rawColumns(['status_badge'])
                ->make(true);
        }
    }

    // Frames Report
    public function frames()
    {
        $stats = [
            'total_frames' => BusinessFrame::where('is_active', true)->count(),
            'rented' => BusinessFrame::where('status', 'rented')->count(),
            'not_rented' => BusinessFrame::where('status', 'not_rented')->count(),
            'under_maintenance' => BusinessFrame::where('status', 'under_maintenance')->count(),
            'total_rent_revenue' => BusinessFrame::where('status', 'rented')->sum('rent_cost'),
        ];
        return view('reports.frames', compact('stats'));
    }

    public function framesData(Request $request)
    {
        $query = BusinessFrame::with(['region', 'district', 'ward', 'village']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addColumn('location', function ($row) {
                $parts = array_filter([$row->ward ? $row->ward->ward : null, $row->district ? $row->district->district : null]);
                return implode(', ', $parts) ?: 'N/A';
            })
            ->addColumn('status_badge', fn($row) => $row->statusBadge)
            ->addColumn('rent_cost_formatted', fn($row) => number_format($row->rent_cost, 2) . ' TZS')
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    // Map Distribution Report
    public function mapDistribution()
    {
        return view('reports.map_distribution');
    }

    public function mapDistributionData(Request $request)
    {
        $entity = $request->get('entity', 'licenses');

        return match ($entity) {
            'markets' => $this->getMarketMapData($request),
            'frames' => $this->getFramesMapData($request),
            default => $this->getLicenseMapData($request),
        };
    }

    private function getLicenseMapData(Request $request)
    {
        $query = BusinessLicense::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['category', 'region', 'district', 'ward']);

        if ($request->filled('category_id')) {
            $query->where('license_category_id', $request->category_id);
        }
        if ($request->filled('license_type')) {
            $query->where('license_type', $request->license_type);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('expiry_date', '>=', now());
            } elseif ($request->status === 'expired') {
                $query->where('expiry_date', '<', now());
            }
        }

        $licenses = $query->get();

        return response()->json([
            'markers' => $licenses->map(function ($license) {
                return [
                    'id' => $license->id,
                    'license_number' => $license->license_number,
                    'owner_name' => $license->owner_name,
                    'business_name' => $license->business_name,
                    'phone' => $license->phone,
                    'category' => $license->category ? $license->category->name : 'N/A',
                    'status' => $license->status,
                    'expiry_date' => $license->expiry_date->format('d M Y'),
                    'lat' => (float) $license->latitude,
                    'lng' => (float) $license->longitude,
                ];
            }),
        ]);
    }

    private function getMarketMapData(Request $request)
    {
        $query = Market::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['region', 'district', 'ward', 'cages']);

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $markets = $query->get();

        return response()->json([
            'markers' => $markets->map(function ($market) {
                return [
                    'id' => $market->id,
                    'name' => $market->name,
                    'location' => ($market->ward ? $market->ward->ward : '') . ', ' . ($market->district ? $market->district->district : ''),
                    'total_cages' => $market->cages->count(),
                    'occupied_cages' => $market->cages->where('status', 'occupied')->count(),
                    'status' => $market->is_active ? 'active' : 'inactive',
                    'lat' => (float) $market->latitude,
                    'lng' => (float) $market->longitude,
                ];
            }),
        ]);
    }

    private function getFramesMapData(Request $request)
    {
        $query = BusinessFrame::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['region', 'district', 'ward', 'village']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $frames = $query->get();

        return response()->json([
            'markers' => $frames->map(function ($frame) {
                return [
                    'id' => $frame->id,
                    'frame_number' => $frame->frame_number,
                    'frame_name' => $frame->frame_name,
                    'status' => $frame->status,
                    'rent_cost' => number_format($frame->rent_cost, 2) . ' TZS',
                    'rented_to' => $frame->rented_to ?: 'Not rented',
                    'location' => ($frame->village ? $frame->village->village : '') . ', ' . ($frame->ward ? $frame->ward->ward : ''),
                    'lat' => (float) $frame->latitude,
                    'lng' => (float) $frame->longitude,
                ];
            }),
        ]);
    }
}
