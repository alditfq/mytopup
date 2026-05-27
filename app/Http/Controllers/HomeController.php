<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Promo;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\Setting;
use App\Models\MarqueeItem;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $games = Game::with('nominals')->get();
        // Only load active promos on the homepage
        $promos = Promo::where('is_active', true)->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $testimonials = Testimonial::where('is_approved', true)->where('is_featured', true)->get();

        // System configurations
        $shopName = Setting::getVal('shop_name', 'GameTopup');
        $logoUrl = Setting::getVal('logo_url', '');
        $marqueeActive = Setting::getVal('marquee_active', 'true');
        $marqueeItems  = MarqueeItem::activeItems();
        $flashSaleEnd  = Setting::getVal('flash_sale_end', '');

        return view('home', compact('games', 'promos', 'faqs', 'testimonials', 'shopName', 'logoUrl', 'marqueeActive', 'marqueeItems', 'flashSaleEnd'));
    }

    public function support()
    {
        return view('support');
    }

    public function getChatMessages()
    {
        $conversationId = session('chat_conversation_id');
        if (!$conversationId) {
            return response()->json(['status' => 'success', 'messages' => []]);
        }

        $messages = ChatMessage::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark any admin replies as read
        ChatMessage::where('conversation_id', $conversationId)
            ->where('sender_type', 'admin')
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'messages' => $messages
        ]);
    }

    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Check if there is an active conversation ID in the session
        $conversationId = session('chat_conversation_id');
        $conversation = null;

        if ($conversationId) {
            $conversation = ChatConversation::find($conversationId);
            // If the conversation is closed, we will create a new one
            if ($conversation && $conversation->status === 'closed') {
                $conversation = null;
            }
        }

        if (!$conversation) {
            // Create a new conversation
            $user = auth()->user();
            $guestName = $user ? $user->name : 'Guest Gamer #' . rand(1000, 9999);
            $guestEmail = $user ? $user->email : null;

            $conversation = ChatConversation::create([
                'user_id' => $user ? $user->id : null,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail,
                'status' => 'open',
                'assigned_admin_id' => null
            ]);

            session(['chat_conversation_id' => $conversation->id]);
        }

        // Store customer message
        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'customer',
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $chatMessage
        ]);
    }
}
