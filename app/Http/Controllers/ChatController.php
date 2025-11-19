<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        // ✅ All users except yourself
        $users = User::where('id', '!=', $auth->id)->get();

        return Inertia::render('Chat/Index', [
            'users' => $users,
            'authUser' => $auth,
        ]);
    }

    // ✅ Load messages between two users
    public function fetchMessages($userId)
    {
        $authId = auth()->id();

        $messages = Chat::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)->where('receiver_id', $userId);
        })
        ->orWhere(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $authId);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($messages);
    }

    // ✅ Send message
    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $data['sender_id'] = auth()->id();

        $msg = Chat::create($data)->load(['sender', 'receiver']);

        return response()->json($msg);
    }
}
