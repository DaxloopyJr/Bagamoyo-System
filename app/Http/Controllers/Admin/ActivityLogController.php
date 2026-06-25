<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('admin.logs.index');
    }

    public function getData(Request $request)
    {
        $query = ActivityLog::with('causer')->latest();

        if ($request->filled('log_name') && $request->log_name !== 'all') {
            $query->where('log_name', $request->log_name);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('causer_name', function ($row) {
                return $row->causer ? $row->causer->name : 'System';
            })
            ->addColumn('date_formatted', function ($row) {
                return $row->created_at->format('d M Y H:i:s');
            })
            ->addColumn('properties_formatted', function ($row) {
                if ($row->properties) {
                    return '<code class="small">' . json_encode($row->properties, JSON_PRETTY_PRINT) . '</code>';
                }
                return '-';
            })
            ->rawColumns(['properties_formatted'])
            ->make(true);
    }
}
