<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::query()
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return response()->json(['message' => 'Message deleted.']);
    }
}
