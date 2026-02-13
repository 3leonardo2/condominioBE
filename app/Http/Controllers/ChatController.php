<?php

namespace App\Http\Controllers;

use App\Models\Message; // Importante importar el modelo
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller {
    // Para cargar los mensajes al entrar al chat
    public function fetchMessages() {
        return Message::orderBy('created_at', 'asc')->get();
    }

    public function sendMessage(Request $request) {
        $validated = $request->validate([
            'message' => 'nullable|string',
            'username' => 'nullable|string',
            'user_id' => 'nullable|integer'
        ]);

        // 1. Guardamos en la base de datos
        $message = Message::create($validated);

        // 2. Disparamos el evento (usamos los datos guardados)
        broadcast(new MessageSent(
            $message->message,
            $message->username,
            $message->user_id
        ))->toOthers(); // toOthers evita que el que envía reciba su propio mensaje por socket

        return response()->json($message);
    }
}