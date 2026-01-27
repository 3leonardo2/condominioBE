<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'username' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        broadcast(new MessageSent(
            $validated['message'],
            $validated['username'],
            $validated['user_id']
        ));

        return response()->json(['status' => 'Message sent!']);
    }
}