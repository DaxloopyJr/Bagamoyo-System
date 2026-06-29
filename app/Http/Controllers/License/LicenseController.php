<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use App\Models\HygieneReminder;
use App\Models\License\BusinessLicense;
use App\Models\License\LicenseCategory;
use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LicenseController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        $categories = LicenseCategory::active()->get();
        $regions = Region::all();
        return view('license.index', compact('categories', 'regions'));
    }

    public function getData(Request $request)
    {
        $query = BusinessLicense::with(['category', 'region', 'district', 'ward', 'village']);

        if ($request->filled('category_id')) {
            $query->where('license_category_id', $request->category_id);
        }
        if ($request->filled('license_type')) {
            $query->where('license_type', $request->license_type);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('status')) {
            if ($request->status === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($request->status === 'active') {
                $query->where('expiry_date', '>=', now());
            } elseif ($request->status === 'expiring_soon') {
                $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
            }
        }
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }
        if ($request->filled('ward_id')) {
            $query->where('ward_id', $request->ward_id);
        }
        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('owner_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : 'N/A';
            })
            ->addColumn('location', function ($row) {
                $parts = array_filter([
                    $row->village ? $row->village->village : null,
                    $row->ward ? $row->ward->ward : null,
                    $row->district ? $row->district->district : null,
                ]);
                return implode(', ', $parts) ?: 'N/A';
            })
            ->addColumn('status', function ($row) {
                return $row->statusBadge;
            })
            ->addColumn('payment_status_badge', function ($row) {
                $badges = [
                    'issue_payment' => '<span class="badge bg-success">Issue Payment</span>',
                    'renewal_payment' => '<span class="badge bg-info">Renewal Payment</span>',
                    'not_paid' => '<span class="badge bg-danger">Not Paid</span>',
                ];
                return $badges[$row->payment_status] ?? '<span class="badge bg-secondary">Unknown</span>';
            })
            ->addColumn('days_remaining', function ($row) {
                $days = $row->daysUntilExpiry;
                if ($days < 0) {
                    return '<span class="text-danger fw-bold">' . abs($days) . ' days overdue</span>';
                }
                return '<span class="' . ($days <= 30 ? 'text-warning fw-bold' : 'text-success') . '">' . $days . ' days</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<a href="' . route('licenses.show', $row) . '" class="btn btn-info" title="View"><i class="bi bi-eye"></i></a>';
                $buttons .= '<a href="' . route('licenses.edit', $row) . '" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-primary send-sms-btn" data-id="' . $row->id . '" data-phone="' . $row->phone . '" data-owner="' . $row->owner_name . '" title="Send SMS"><i class="bi bi-chat-dots"></i></button>';
                $buttons .= '<button type="button" class="btn btn-secondary hygiene-btn" data-id="' . $row->id . '" data-owner="' . $row->owner_name . '" title="Hygiene Reminder"><i class="bi bi-bucket"></i></button>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status', 'payment_status_badge', 'days_remaining', 'action'])
            ->orderColumn('expiry_date', 'expiry_date $1')
            ->make(true);
    }

    public function create()
    {
        $categories = LicenseCategory::active()->get();
        $regions = Region::all();
        return view('license.create', compact('categories', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'license_number' => 'nullable|string|unique:business_licenses,license_number',
            'license_category_id' => 'required|exists:license_categories,id',
            'license_type' => 'required|in:mid_year,annual',
            'issue_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:issue_payment,renewal_payment,not_paid',
            'business_name' => 'required|string|max:255',
            'business_description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'village_id' => 'nullable|exists:villages,id',
            'street' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $license = BusinessLicense::create($validated);
        $this->logActivity("Created business license {$license->license_number} for {$license->owner_name}", $license, 'created');

        return $this->successResponse('Business license created successfully.', route('licenses.index'));
    }

    public function show(BusinessLicense $license)
    {
        $license->load(['category', 'region', 'district', 'ward', 'village']);
        return view('license.show', compact('license'));
    }

    public function edit(BusinessLicense $license)
    {
        $categories = LicenseCategory::active()->get();
        $regions = Region::all();
        $districts = $license->region_id ? District::where('region_id', $license->region_id)->get() : [];
        $wards = $license->district_id ? Ward::where('district_id', $license->district_id)->get() : [];
        $villages = $license->ward_id ? Village::where('ward_id', $license->ward_id)->get() : [];

        return view('license.edit', compact('license', 'categories', 'regions', 'districts', 'wards', 'villages'));
    }

    public function update(Request $request, BusinessLicense $license)
    {
        $validated = $request->validate([
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'license_category_id' => 'required|exists:license_categories,id',
            'license_type' => 'required|in:mid_year,annual',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'payment_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:issue_payment,renewal_payment,not_paid',
            'business_name' => 'required|string|max:255',
            'business_description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'village_id' => 'nullable|exists:villages,id',
            'street' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if (!empty($validated['expiry_date'])) {
            unset($validated['expiry_date']);
        }

        $license->update($validated);
        $this->logActivity("Updated business license {$license->license_number}", $license, 'updated');

        return $this->successResponse('Business license updated successfully.', route('licenses.index'));
    }

    public function destroy(BusinessLicense $license)
    {
        $license->delete();
        $this->logActivity("Deleted business license {$license->license_number}", $license, 'deleted');

        return $this->successResponse('Business license deleted successfully.');
    }

    public function sendReminder(Request $request, BusinessLicense $license)
    {
        $request->validate([
            'message' => 'nullable|string',
        ]);

        $daysUntilExpiry = $license->daysUntilExpiry;
        $defaultMessage = $daysUntilExpiry > 0
            ? "Habari {$license->owner_name}, leseni yako ya biashara {$license->license_number} itaisha baada ya siku {$daysUntilExpiry} tarehe {$license->expiry_date->format('d M Y')}. Tafadhali fanya upya katika Ofisi ya Halmashauri ya Manispaa ya Bagamoyo. Asante."
            : "Habari {$license->owner_name}, leseni yako ya biashara {$license->license_number} iliisha tarehe {$license->expiry_date->format('d M Y')}. Tafadhali fanya upya mara moja. Asante.";

        $message = $request->get('message', $defaultMessage);

        $result = $this->smsService->send($license->phone, $message, $license, 'license_reminder');

        if ($result['success']) {
            $this->logActivity("Sent SMS reminder to {$license->owner_name} ({$license->phone})", $license, 'sms_sent', ['message' => $message]);
            return $this->successResponse('SMS reminder sent successfully.');
        }

        return $this->errorResponse('Failed to send SMS: ' . ($result['error'] ?? 'Unknown error'));
    }

    public function sendHygieneReminder(Request $request, BusinessLicense $license)
    {
        $message = "Habari {$license->owner_name}, tunakukumbusha kudumisha usafi katika biashara yako {$license->business_name}. Tafadhali hakikisha utupaji wa taka unaofaa na mazingira safi. Halmashauri ya Manispaa ya Bagamoyo.";

        $result = $this->smsService->send($license->phone, $message, $license, 'hygiene_reminder');

        if ($result['success']) {
            HygieneReminder::create([
                'license_id' => $license->id,
                'message' => $message,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $license->update(['hygiene_reminder_sent' => true]);
            $this->logActivity("Sent hygiene reminder to {$license->owner_name}", $license, 'hygiene_reminder', ['message' => $message]);

            return $this->successResponse('Hygiene reminder sent successfully.');
        }

        return $this->errorResponse('Failed to send hygiene reminder: ' . ($result['error'] ?? 'Unknown error'));
    }

    public function sendBulkSms(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:480',
            'recipient_type' => 'required|in:all,all_inactive,selected',
            'license_ids' => 'nullable|array',
            'send_to_all' => 'nullable|boolean',
        ]);

        $message = $request->get('message');
        $results = ['sent' => 0, 'failed' => 0];
        $recipientType = $request->get('recipient_type', 'all');

        if ($recipientType === 'all_inactive') {
            $licenses = BusinessLicense::where('is_active', false)->get();
        } elseif ($request->boolean('send_to_all') || $recipientType === 'all') {
            $licenses = BusinessLicense::where('is_active', true)->get();
        } else {
            $licenses = BusinessLicense::whereIn('id', $request->get('license_ids', []))->get();
        }

        foreach ($licenses as $license) {
            $result = $this->smsService->send($license->phone, $message, $license, 'bulk');
            if ($result['success']) {
                $results['sent']++;
            } else {
                $results['failed']++;
            }
        }

        $this->logActivity("Sent bulk SMS to {$results['sent']} business owners ({$recipientType})", null, 'bulk_sms', ['sent' => $results['sent'], 'failed' => $results['failed']]);

        return $this->successResponse("Bulk SMS sent: {$results['sent']} successful, {$results['failed']} failed.");
    }
}
