<?php

namespace App\Http\Controllers;

use App\Models\BusinessFrame\BusinessFrame;
use App\Models\Fishery\Fisherman;
use App\Models\Fishery\FishingBoat;
use App\Models\License\BusinessLicense;
use App\Models\Market\Market;
use App\Models\Market\MarketCage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getDashboardStats();
        return view('dashboard.index', compact('stats'));
    }

    public function getStats(Request $request)
    {
        return response()->json($this->getDashboardStats());
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'licenses');
        $year = $request->get('year', now()->year);

        $chartData = match ($type) {
            'licenses' => $this->getLicenseChartData($year),
            'revenue' => $this->getRevenueChartData($year),
            'fishery' => $this->getFisheryChartData(),
            'frames' => $this->getFramesChartData(),
            default => $this->getLicenseChartData($year),
        };

        return response()->json($chartData);
    }

    private function getDashboardStats()
    {
        $now = now();
        $today = $now->format('Y-m-d');
        $startOfMonth = $now->copy()->startOfMonth()->format('Y-m-d');
        $startOfThreeMonths = $now->copy()->addMonths(3)->format('Y-m-d');
        $startOfYear = $now->copy()->startOfYear()->format('Y-m-d');
        $endOfYear = $now->copy()->endOfYear()->format('Y-m-d');

        return [
            'licenses_expired_today' => BusinessLicense::whereDate('expiry_date', $today)
                ->where('is_active', true)->count(),
            'licenses_expired_this_month' => BusinessLicense::whereBetween('expiry_date', [$startOfMonth, $now->copy()->endOfMonth()])
                ->where('is_active', true)->count(),
            'licenses_expiring_three_months' => BusinessLicense::whereBetween('expiry_date', [$today, $startOfThreeMonths])
                ->where('is_active', true)->count(),
            'licenses_expired_this_year' => BusinessLicense::whereBetween('expiry_date', [$startOfYear, $endOfYear])
                ->where('is_active', true)->count(),
            'active_licenses' => BusinessLicense::where('is_active', true)
                ->where('expiry_date', '>=', $today)->count(),
            'total_licenses' => BusinessLicense::count(),
            'total_fishermen' => Fisherman::where('is_active', true)->count(),
            'total_fishing_boats' => FishingBoat::where('is_active', true)->count(),
            'total_markets' => Market::where('is_active', true)->count(),
            'total_cages' => MarketCage::count(),
            'occupied_cages' => MarketCage::where('status', 'occupied')->count(),
            'total_frames' => BusinessFrame::where('is_active', true)->count(),
            'rented_frames' => BusinessFrame::where('status', 'rented')->count(),
            'not_rented_frames' => BusinessFrame::where('status', 'not_rented')->count(),
            'total_revenue_month' => BusinessLicense::where('payment_status', '!=', 'not_paid')
                ->whereMonth('updated_at', $now->month)
                ->whereYear('updated_at', $now->year)
                ->sum('payment_amount'),
            'recent_activities' => \App\Models\ActivityLog::with('causer')
                ->latest()
                ->take(10)
                ->get(),
            'expiring_soon' => BusinessLicense::where('expiry_date', '>=', $today)
                ->where('expiry_date', '<=', $now->copy()->addDays(30))
                ->where('is_active', true)
                ->with('category')
                ->orderBy('expiry_date')
                ->take(10)
                ->get(),
        ];
    }

    private function getLicenseChartData($year)
    {
        $data = [];
        $labels = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create($year, $month)->format('M');
            $data[] = BusinessLicense::whereYear('issue_date', $year)
                ->whereMonth('issue_date', $month)
                ->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Licenses Issued',
                    'data' => $data,
                    'backgroundColor' => 'rgba(30, 144, 72, 0.7)',
                    'borderColor' => '#1E9048',
                    'borderWidth' => 1,
                ]
            ]
        ];
    }

    private function getRevenueChartData($year)
    {
        $data = [];
        $labels = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create($year, $month)->format('M');
            $data[] = (float) BusinessLicense::whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month)
                ->where('payment_status', '!=', 'not_paid')
                ->sum('payment_amount');
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Revenue (TZS)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 196, 0, 0.7)',
                    'borderColor' => '#FFC400',
                    'borderWidth' => 1,
                ]
            ]
        ];
    }

    private function getFisheryChartData()
    {
        $fishermenByWard = Fisherman::selectRaw('wards.ward as name, COUNT(*) as count')
            ->join('wards', 'fishermen.ward_id', '=', 'wards.id')
            ->where('fishermen.is_active', true)
            ->groupBy('wards.id', 'wards.ward')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return [
            'labels' => $fishermenByWard->pluck('name'),
            'datasets' => [
                [
                    'label' => 'Fishermen',
                    'data' => $fishermenByWard->pluck('count'),
                    'backgroundColor' => ['#1E9048', '#1DA1D4', '#FFC400', '#000000', '#FFD700', '#2E7D32', '#0288D1', '#F57C00', '#C62828', '#6A1B9A'],
                    'borderWidth' => 0,
                ]
            ]
        ];
    }

    private function getFramesChartData()
    {
        $rented = BusinessFrame::where('status', 'rented')->count();
        $notRented = BusinessFrame::where('status', 'not_rented')->count();
        $maintenance = BusinessFrame::where('status', 'under_maintenance')->count();

        return [
            'labels' => ['Rented', 'Not Rented', 'Under Maintenance'],
            'datasets' => [
                [
                    'data' => [$rented, $notRented, $maintenance],
                    'backgroundColor' => ['#1E9048', '#C62828', '#FFC400'],
                    'borderWidth' => 0,
                ]
            ]
        ];
    }
}
