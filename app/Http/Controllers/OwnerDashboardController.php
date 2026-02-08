<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SMD\Common\ReservationSystem\Models\RsProperty;
use SMD\Common\ReservationSystem\Models\RsPropertyImage;
use SMD\Common\ReservationSystem\Models\RsPropertyInquiry;
use SMD\Common\ReservationSystem\Models\RsPropertyView;
use Illuminate\Support\Facades\Storage;

class OwnerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the owner dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get owner's properties
        $properties = RsProperty::where('owner_id', $user->id)
            ->where('active', true)
            ->with(['images'])
            ->get();

        $propertyIds = $properties->pluck('id')->toArray();

        // Get statistics
        $totalViews = RsPropertyView::whereIn('property_id', $propertyIds)->count();
        $viewsThisMonth = RsPropertyView::whereIn('property_id', $propertyIds)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        $viewsLastMonth = RsPropertyView::whereIn('property_id', $propertyIds)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        $totalInquiries = RsPropertyInquiry::whereIn('property_id', $propertyIds)->count();
        $newInquiries = RsPropertyInquiry::whereIn('property_id', $propertyIds)
            ->where('status', 'new')
            ->count();

        // Get recent inquiries
        $recentInquiries = RsPropertyInquiry::whereIn('property_id', $propertyIds)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Views by property
        $viewsByProperty = [];
        foreach ($properties as $property) {
            $viewsByProperty[$property->id] = RsPropertyView::where('property_id', $property->id)->count();
        }

        // Calculate view trend
        $viewTrend = $viewsLastMonth > 0 
            ? round((($viewsThisMonth - $viewsLastMonth) / $viewsLastMonth) * 100) 
            : ($viewsThisMonth > 0 ? 100 : 0);

        return view('tref.owner-dashboard', compact(
            'user',
            'properties',
            'totalViews',
            'viewsThisMonth',
            'viewTrend',
            'totalInquiries',
            'newInquiries',
            'recentInquiries',
            'viewsByProperty'
        ));
    }

    /**
     * Show property edit form
     */
    public function editProperty($id)
    {
        $user = Auth::user();
        $property = RsProperty::where('id', $id)
            ->where('owner_id', $user->id)
            ->with(['images'])
            ->firstOrFail();

        return view('tref.edit-property', compact('property'));
    }

    /**
     * Update property
     */
    public function updateProperty(Request $request, $id)
    {
        $user = Auth::user();
        $property = RsProperty::where('id', $id)
            ->where('owner_id', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|integer',
            'bedroom_count' => 'required|integer|min:1',
            'bathroom_count' => 'required|integer|min:1',
            'guest_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|in:USD,GBP,EUR,CAD,ILS',
            'cleaning_fee' => 'nullable|numeric|min:0',
            'map_address' => 'nullable|string',
            'additional_information' => 'nullable|string',
            'amenities' => 'nullable|array',
            'kosher_info' => 'nullable|array',
        ]);

        $property->update([
            'title' => $validated['title'],
            'property_type' => $validated['property_type'],
            'bedroom_count' => $validated['bedroom_count'],
            'bathroom_count' => $validated['bathroom_count'],
            'guest_count' => $validated['guest_count'],
            'price' => $validated['price'],
            'currency' => $validated['currency'],
            'cleaning_fee' => $validated['cleaning_fee'] ?? 0,
            'map_address' => $validated['map_address'] ?? '',
            'additional_information' => $validated['additional_information'] ?? '',
            'amenities' => json_encode($validated['amenities'] ?? []),
            'kosher_info' => json_encode($validated['kosher_info'] ?? []),
        ]);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('property-images', 'public');
                RsPropertyImage::create([
                    'property_id' => $property->id,
                    'image_url' => '/storage/' . $path,
                ]);
            }
        }

        return redirect()->route('owner.dashboard')->with('success', 'Property updated successfully!');
    }

    /**
     * Delete property image
     */
    public function deleteImage(Request $request, $id)
    {
        $user = Auth::user();
        $image = RsPropertyImage::whereHas('property', function($q) use ($user) {
            $q->where('owner_id', $user->id);
        })->findOrFail($id);

        // Delete file from storage
        $path = str_replace('/storage/', '', $image->image_url);
        Storage::disk('public')->delete($path);

        $image->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Show inquiries/leads
     */
    public function inquiries()
    {
        $user = Auth::user();
        $propertyIds = RsProperty::where('owner_id', $user->id)->pluck('id');

        $inquiries = RsPropertyInquiry::whereIn('property_id', $propertyIds)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tref.inquiries', compact('inquiries'));
    }

    /**
     * Show single inquiry
     */
    public function showInquiry($id)
    {
        $user = Auth::user();
        $inquiry = RsPropertyInquiry::whereHas('property', function($q) use ($user) {
            $q->where('owner_id', $user->id);
        })->with('property')->findOrFail($id);

        // Mark as read
        if ($inquiry->status === 'new') {
            $inquiry->markAsRead();
        }

        return view('tref.inquiry-detail', compact('inquiry'));
    }

    /**
     * Update inquiry status
     */
    public function updateInquiry(Request $request, $id)
    {
        $user = Auth::user();
        $inquiry = RsPropertyInquiry::whereHas('property', function($q) use ($user) {
            $q->where('owner_id', $user->id);
        })->findOrFail($id);

        $inquiry->update([
            'status' => $request->status,
            'owner_notes' => $request->owner_notes,
        ]);

        if ($request->status === 'responded') {
            $inquiry->update(['responded_at' => now()]);
        }

        return redirect()->back()->with('success', 'Inquiry updated!');
    }

    /**
     * Analytics page
     */
    public function analytics()
    {
        $user = Auth::user();
        $properties = RsProperty::where('owner_id', $user->id)->get();
        $propertyIds = $properties->pluck('id')->toArray();

        // Daily views for last 30 days
        $dailyViews = RsPropertyView::whereIn('property_id', $propertyIds)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Views by source
        $viewsBySource = RsPropertyView::whereIn('property_id', $propertyIds)
            ->selectRaw('source, COUNT(*) as count')
            ->groupBy('source')
            ->get();

        // Views per property
        $viewsByProperty = RsPropertyView::whereIn('property_id', $propertyIds)
            ->join('rs_properties', 'rs_property_views.property_id', '=', 'rs_properties.id')
            ->selectRaw('rs_properties.title as name, COUNT(*) as views')
            ->groupBy('rs_properties.id', 'rs_properties.title')
            ->get();

        return view('tref.analytics', compact('properties', 'dailyViews', 'viewsBySource', 'viewsByProperty'));
    }
}
