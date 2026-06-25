<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $regionsCount = Region::count();
        $districtsCount = District::count();
        $wardsCount = Ward::count();
        $villagesCount = Village::count();

        return view('admin.locations.index', compact('regionsCount', 'districtsCount', 'wardsCount', 'villagesCount'));
    }

    public function regions()
    {
        $regions = Region::withCount(['districts'])->get();
        return view('admin.locations.regions', compact('regions'));
    }

    public function districts()
    {
        $districts = District::with(['region'])->withCount(['wards'])->get();
        return view('admin.locations.districts', compact('districts'));
    }

    public function wards()
    {
        $wards = Ward::with(['district.region'])->withCount(['villages'])->get();
        return view('admin.locations.wards', compact('wards'));
    }

    public function villages()
    {
        $villages = Village::with(['ward.district.region'])->get();
        return view('admin.locations.villages', compact('villages'));
    }

    // AJAX endpoints for cascading dropdowns
    public function getDistricts($regionId)
    {
        $districts = District::where('region_id', $regionId)->get(['id', 'district as name']);
        return response()->json($districts);
    }

    public function getWards($districtId)
    {
        $wards = Ward::where('district_id', $districtId)->get(['id', 'ward as name']);
        return response()->json($wards);
    }

    public function getVillages($wardId)
    {
        $villages = Village::where('ward_id', $wardId)->get(['id', 'village as name']);
        return response()->json($villages);
    }
}
