<?php

namespace App\Http\Controllers;

use App\Events\NewChat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mensaje' => 'required|string|max:1000',
            'usuario' => 'nullable|string|max:255'
        ]);

        $user = Auth::user();
        
        if (!$user) {
            $user = (object) ['name' => $validated['usuario'] ?? 'Anónimo'];
        }

        event(new NewChat($validated['mensaje'], $user));

        return response()->json(['success' => true]);
    }

    public function index()
    {
        return inertia('Chat');
    }
}