<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings\MobileAdvertisement;
use App\Models\Settings\MobileOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MobileAppController extends Controller
{
    // Advertisements
    public function advertisements()
    {
        return view('admin.mobile_app.advertisements');
    }

    public function advertisementsData(Request $request)
    {
        $query = MobileAdvertisement::with('approver');

        return DataTables::of($query)
            ->addColumn('subscription_period', function ($row) {
                return $row->subscription_start->format('d M Y') . ' - ' . $row->subscription_end->format('d M Y');
            })
            ->addColumn('status_badge', function ($row) {
                return $row->statusBadge;
            })
            ->addColumn('featured_badge', function ($row) {
                return $row->is_featured ? '<span class="badge bg-warning"><i class="bi bi-star-fill"></i> Featured</span>' : '';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                if ($row->status === 'pending') {
                    $buttons .= '<button type="button" class="btn btn-success approve-btn" data-id="' . $row->id . '" title="Approve"><i class="bi bi-check-lg"></i></button>';
                }
                $buttons .= '<a href="' . route('admin.mobile-app.advertisements.edit', $row) . '" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status_badge', 'featured_badge', 'action'])
            ->make(true);
    }

    public function createAdvertisement()
    {
        return view('admin.mobile_app.advertisements_create');
    }

    public function storeAdvertisement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'business_type' => 'nullable|string|max:100',
            'subscription_fee' => 'required|numeric|min:0',
            'subscription_start' => 'required|date',
            'subscription_end' => 'required|date|after:subscription_start',
            'image' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('advertisements', 'public');
        }

        $ad = MobileAdvertisement::create($validated);
        $this->logActivity("Created advertisement {$ad->title}", $ad, 'created');

        return $this->successResponse('Advertisement created successfully.', route('admin.mobile-app.advertisements'));
    }

    public function editAdvertisement(MobileAdvertisement $ad)
    {
        return view('admin.mobile_app.advertisements_edit', compact('ad'));
    }

    public function updateAdvertisement(Request $request, MobileAdvertisement $ad)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'business_type' => 'nullable|string|max:100',
            'subscription_fee' => 'required|numeric|min:0',
            'subscription_start' => 'required|date',
            'subscription_end' => 'required|date|after:subscription_start',
            'image' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('image')) {
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $validated['image'] = $request->file('image')->store('advertisements', 'public');
        }

        $ad->update($validated);
        $this->logActivity("Updated advertisement {$ad->title}", $ad, 'updated');

        return $this->successResponse('Advertisement updated successfully.', route('admin.mobile-app.advertisements'));
    }

    public function destroyAdvertisement(MobileAdvertisement $ad)
    {
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }
        $ad->delete();
        $this->logActivity("Deleted advertisement {$ad->title}", $ad, 'deleted');

        return $this->successResponse('Advertisement deleted successfully.');
    }

    public function approveAdvertisement(MobileAdvertisement $ad)
    {
        $ad->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->logActivity("Approved advertisement {$ad->title}", $ad, 'approved');

        return $this->successResponse('Advertisement approved successfully.');
    }

    // Opportunities
    public function opportunities()
    {
        return view('admin.mobile_app.opportunities');
    }

    public function opportunitiesData(Request $request)
    {
        $query = MobileOpportunity::with('creator');

        return DataTables::of($query)
            ->addColumn('type_badge', function ($row) {
                $badges = [
                    'business' => '<span class="badge bg-primary">Business</span>',
                    'employment' => '<span class="badge bg-success">Employment</span>',
                    'tender' => '<span class="badge bg-warning text-dark">Tender</span>',
                    'investment' => '<span class="badge bg-info">Investment</span>',
                    'training' => '<span class="badge bg-secondary">Training</span>',
                    'other' => '<span class="badge bg-dark">Other</span>',
                ];
                return $badges[$row->opportunity_type] ?? '<span class="badge bg-dark">Other</span>';
            })
            ->addColumn('status_badge', function ($row) {
                return $row->statusBadge;
            })
            ->addColumn('deadline_formatted', function ($row) {
                return $row->deadline ? $row->deadline->format('d M Y') : 'N/A';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group btn-group-sm">';
                $buttons .= '<button type="button" class="btn btn-' . ($row->is_featured ? 'warning' : 'outline-warning') . ' toggle-featured-btn" data-id="' . $row->id . '" title="Toggle Featured"><i class="bi bi-star"></i></button>';
                $buttons .= '<a href="' . route('admin.mobile-app.opportunities.edit', $row) . '" class="btn btn-info" title="Edit"><i class="bi bi-pencil"></i></a>';
                $buttons .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['type_badge', 'status_badge', 'action'])
            ->make(true);
    }

    public function createOpportunity()
    {
        return view('admin.mobile_app.opportunities_create');
    }

    public function storeOpportunity(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'opportunity_type' => 'required|in:business,employment,tender,investment,training,other',
            'organization' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'deadline' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url|max:500',
            'is_featured' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'published';
        $validated['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('opportunities', 'public');
        }

        $opportunity = MobileOpportunity::create($validated);
        $this->logActivity("Created opportunity {$opportunity->title}", $opportunity, 'created');

        return $this->successResponse('Opportunity created successfully.', route('admin.mobile-app.opportunities'));
    }

    public function editOpportunity(MobileOpportunity $opportunity)
    {
        return view('admin.mobile_app.opportunities_edit', compact('opportunity'));
    }

    public function updateOpportunity(Request $request, MobileOpportunity $opportunity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'opportunity_type' => 'required|in:business,employment,tender,investment,training,other',
            'organization' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'deadline' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('image')) {
            if ($opportunity->image) {
                Storage::disk('public')->delete($opportunity->image);
            }
            $validated['image'] = $request->file('image')->store('opportunities', 'public');
        }

        $opportunity->update($validated);
        $this->logActivity("Updated opportunity {$opportunity->title}", $opportunity, 'updated');

        return $this->successResponse('Opportunity updated successfully.', route('admin.mobile-app.opportunities'));
    }

    public function destroyOpportunity(MobileOpportunity $opportunity)
    {
        if ($opportunity->image) {
            Storage::disk('public')->delete($opportunity->image);
        }
        $opportunity->delete();
        $this->logActivity("Deleted opportunity {$opportunity->title}", $opportunity, 'deleted');

        return $this->successResponse('Opportunity deleted successfully.');
    }

    public function toggleFeaturedOpportunity(MobileOpportunity $opportunity)
    {
        $opportunity->update(['is_featured' => !$opportunity->is_featured]);
        $status = $opportunity->is_featured ? 'featured' : 'unfeatured';
        $this->logActivity("{$status} opportunity {$opportunity->title}", $opportunity, 'featured_toggled');

        return $this->successResponse("Opportunity {$status} successfully.");
    }
}
