<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Settings\MobileAdvertisement;
use App\Models\Settings\MobileOpportunity;
use Illuminate\Http\Request;

class MobileAppApiController extends Controller
{
    // Opportunities
    public function opportunities(Request $request)
    {
        $query = MobileOpportunity::where('status', 'published')
            ->select('id', 'title', 'description', 'opportunity_type', 'organization', 'deadline', 'image', 'link_url', 'is_featured', 'created_at')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('opportunity_type', $request->type);
        }

        $opportunities = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $opportunities,
        ]);
    }

    public function opportunityDetail($id)
    {
        $opportunity = MobileOpportunity::where('status', 'published')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $opportunity,
        ]);
    }

    // Advertisements
    public function advertisements(Request $request)
    {
        $query = MobileAdvertisement::where('status', 'active')
            ->whereDate('subscription_end', '>=', now())
            ->select('id', 'title', 'description', 'contact_person', 'contact_phone', 'business_type', 'image', 'is_featured', 'subscription_fee', 'created_at')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('business_type', $request->type);
        }

        $ads = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $ads,
        ]);
    }

    public function advertisementDetail($id)
    {
        $ad = MobileAdvertisement::where('status', 'active')->findOrFail($id);

        // Increment view count
        $ad->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => $ad,
        ]);
    }

    public function featuredAds()
    {
        $ads = MobileAdvertisement::where('status', 'active')
            ->where('is_featured', true)
            ->whereDate('subscription_end', '>=', now())
            ->select('id', 'title', 'description', 'image', 'business_type')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $ads,
        ]);
    }

    public function trackClick($id)
    {
        $ad = MobileAdvertisement::findOrFail($id);
        $ad->increment('click_count');

        return response()->json([
            'success' => true,
            'message' => 'Click tracked',
        ]);
    }

    // Authentication (simplified for mobile)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'business_type' => 'nullable|string|max:100',
        ]);

        $validated['subscription_fee'] = 50000; // Annual fee in TZS
        $validated['subscription_start'] = now();
        $validated['subscription_end'] = now()->addYear();
        $validated['status'] = 'pending';

        $ad = MobileAdvertisement::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Subscription request submitted. Pending approval.',
            'data' => $ad,
        ]);
    }

    public function mySubscriptions(Request $request)
    {
        // This would filter by authenticated user in production
        $ads = MobileAdvertisement::where('contact_phone', $request->user()->phone ?? '')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $ads,
        ]);
    }

    public function renewSubscription(Request $request, $id)
    {
        $ad = MobileAdvertisement::findOrFail($id);

        $ad->update([
            'subscription_end' => now()->addYear(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription renewed successfully.',
            'data' => $ad,
        ]);
    }
}
