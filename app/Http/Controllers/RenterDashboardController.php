<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SMD\Common\ReservationSystem\Models\RsProperty;
use SMD\Common\ReservationSystem\Models\RsSavedProperty;
use SMD\Common\ReservationSystem\Models\RsPropertyReview;
use SMD\Common\ReservationSystem\Models\RsConversation;
use SMD\Common\ReservationSystem\Models\RsMessage;
use SMD\Common\ReservationSystem\Models\RsReservation;

class RenterDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the renter dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get saved properties
        $savedProperties = RsSavedProperty::where('user_id', $user->id)
            ->with(['property.images'])
            ->latest()
            ->take(6)
            ->get();

        // Get recent conversations
        $conversations = RsConversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['property', 'latestMessage', 'userOne', 'userTwo'])
            ->orderBy('last_message_at', 'desc')
            ->take(5)
            ->get();

        // Get unread message count
        $unreadCount = RsMessage::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Get user's reviews
        $reviewCount = RsPropertyReview::where('user_id', $user->id)->count();

        // Get reservations count (if any exist)
        $reservationCount = 0;
        try {
            $reservationCount = RsReservation::where('user_id', $user->id)->count();
        } catch (\Exception $e) {
            // Table might not exist
        }

        return view('tref.renter-dashboard', compact(
            'savedProperties',
            'conversations',
            'unreadCount',
            'reviewCount',
            'reservationCount'
        ));
    }

    /**
     * Show saved properties
     */
    public function savedProperties()
    {
        $user = Auth::user();

        $savedProperties = RsSavedProperty::where('user_id', $user->id)
            ->with(['property.images', 'property.owner'])
            ->latest()
            ->paginate(12);

        return view('tref.saved-properties', compact('savedProperties'));
    }

    /**
     * Toggle save property
     */
    public function toggleSave(Request $request, $propertyId)
    {
        $user = Auth::user();
        $saved = RsSavedProperty::toggleSave($user->id, $propertyId);

        if ($request->ajax()) {
            return response()->json([
                'saved' => $saved,
                'message' => $saved ? 'Property saved!' : 'Property removed from saved'
            ]);
        }

        return back()->with('success', $saved ? 'Property saved!' : 'Property removed from saved');
    }

    /**
     * Show conversations/messages
     */
    public function messages()
    {
        $user = Auth::user();

        $conversations = RsConversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['property', 'latestMessage', 'userOne', 'userTwo'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return view('tref.renter-messages', compact('conversations'));
    }

    /**
     * Show single conversation
     */
    public function showConversation($id)
    {
        $user = Auth::user();

        $conversation = RsConversation::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_one_id', $user->id)
                  ->orWhere('user_two_id', $user->id);
            })
            ->with(['property', 'userOne', 'userTwo'])
            ->firstOrFail();

        // Get messages
        $messages = RsMessage::where('conversation_id', $conversation->id)
            ->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        RsMessage::where('conversation_id', $conversation->id)
            ->where('recipient_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $otherUser = $conversation->getOtherUser($user->id);

        return view('tref.conversation', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|integer',
            'message' => 'required|string|max:2000',
            'property_id' => 'nullable|integer',
            'conversation_id' => 'nullable|integer',
        ]);

        $user = Auth::user();

        $message = RsMessage::sendMessage(
            $user->id,
            $request->recipient_id,
            $request->message,
            $request->property_id,
            $request->conversation_id
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender')
            ]);
        }

        return back()->with('success', 'Message sent!');
    }

    /**
     * Start a new conversation with a host
     */
    public function startConversation(Request $request, $propertyId)
    {
        $user = Auth::user();
        
        $property = RsProperty::with('owner')->findOrFail($propertyId);
        
        // Get or create conversation
        $conversation = RsConversation::getOrCreate($user->id, $property->owner_id, $propertyId);

        return redirect()->route('renter.conversation', $conversation->id);
    }

    /**
     * Show reviews page
     */
    public function reviews()
    {
        $user = Auth::user();

        $reviews = RsPropertyReview::where('user_id', $user->id)
            ->with(['property.images'])
            ->latest()
            ->paginate(10);

        return view('tref.renter-reviews', compact('reviews'));
    }

    /**
     * Show review form for a property
     */
    public function showReviewForm($propertyId)
    {
        $user = Auth::user();
        
        $property = RsProperty::with('images')->findOrFail($propertyId);
        
        // Check if user already reviewed this property
        $existingReview = RsPropertyReview::where('user_id', $user->id)
            ->where('property_id', $propertyId)
            ->first();

        return view('tref.review-form', compact('property', 'existingReview'));
    }

    /**
     * Submit a review
     */
    public function submitReview(Request $request, $propertyId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();

        // Check if already reviewed
        $existing = RsPropertyReview::where('user_id', $user->id)
            ->where('property_id', $propertyId)
            ->first();

        if ($existing) {
            $existing->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
            $message = 'Review updated!';
        } else {
            RsPropertyReview::create([
                'user_id' => $user->id,
                'property_id' => $propertyId,
                'rating' => $request->rating,
                'review' => $request->review,
                'is_approved' => false, // Will need admin approval
            ]);
            $message = 'Review submitted! It will appear after approval.';
        }

        return redirect()->route('renter.reviews')->with('success', $message);
    }
}
