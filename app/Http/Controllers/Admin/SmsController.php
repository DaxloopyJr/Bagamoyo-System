<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License\BusinessLicense;
use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function create()
    {
        $activeLicenses = BusinessLicense::where('is_active', true)
            ->where('expiry_date', '>=', now())
            ->orderBy('owner_name')
            ->get(['id', 'owner_name', 'phone']);

        return view('sms.send', compact('activeLicenses'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:single,multiple,all',
            'license_ids' => 'required_if:recipient_type,multiple|array',
            'license_ids.*' => 'exists:business_licenses,id',
            'single_license_id' => 'required_if:recipient_type,single|exists:business_licenses,id',
            'message' => 'required|string|max:480',
        ]);

        $message = $validated['message'];
        $results = ['sent' => 0, 'failed' => 0];

        switch ($validated['recipient_type']) {
            case 'single':
                $license = BusinessLicense::find($validated['single_license_id']);
                try {
                    $this->smsService->send($license->phone, $message, $license, 'custom');
                    $results['sent']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                }
                break;

            case 'multiple':
                $licenses = BusinessLicense::whereIn('id', $validated['license_ids'])->get();
                foreach ($licenses as $license) {
                    try {
                        $this->smsService->send($license->phone, $message, $license, 'custom');
                        $results['sent']++;
                    } catch (\Exception $e) {
                        $results['failed']++;
                    }
                }
                break;

            case 'all':
                BusinessLicense::where('is_active', true)->chunk(100, function ($licenses) use ($message, &$results) {
                    foreach ($licenses as $license) {
                        try {
                            $this->smsService->send($license->phone, $message, $license, 'custom');
                            $results['sent']++;
                        } catch (\Exception $e) {
                            $results['failed']++;
                        }
                    }
                });
                break;
        }

        $this->logActivity("Sent custom SMS: {$results['sent']} sent, {$results['failed']} failed", null, 'sms_custom', ['sent' => $results['sent'], 'failed' => $results['failed']]);

        return $this->successResponse("SMS sent: {$results['sent']} successful, {$results['failed']} failed.");
    }

    public function logs()
    {
        return view('sms.logs');
    }

    public function logsData(Request $request)
    {
        $query = SmsLog::with('sender')->latest();

        if ($request->filled('sms_type') && $request->sms_type !== 'all') {
            $query->where('sms_type', $request->sms_type);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return DataTables::of($query)
            ->addColumn('sender_name', function ($row) {
                return $row->sender ? $row->sender->name : 'System';
            })
            ->addColumn('message_preview', function ($row) {
                return \Illuminate\Support\Str::limit($row->message, 60);
            })
            ->addColumn('date_formatted', function ($row) {
                return $row->created_at->format('d M Y H:i:s');
            })
            ->addColumn('status_badge', function ($row) {
                return $row->statusBadge;
            })
            ->addColumn('type_badge', function ($row) {
                $badges = [
                    'license_reminder_21' => '<span class="badge bg-info">21 Days</span>',
                    'license_reminder_14' => '<span class="badge bg-warning text-dark">14 Days</span>',
                    'license_reminder_7' => '<span class="badge bg-danger">7 Days</span>',
                    'license_expired_1' => '<span class="badge bg-dark">Expired</span>',
                    'custom' => '<span class="badge bg-primary">Custom</span>',
                    'hygiene_reminder' => '<span class="badge bg-success">Hygiene</span>',
                    'bulk' => '<span class="badge bg-secondary">Bulk</span>',
                ];
                return $badges[$row->sms_type] ?? '<span class="badge bg-secondary">Unknown</span>';
            })
            ->rawColumns(['status_badge', 'type_badge'])
            ->make(true);
    }
}
