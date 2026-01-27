<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string'],
        ]);

        $message = ContactMessage::create($data);
        $recipient = WebsiteSetting::query()->value('email') ?: 'info@snc.asia';

        try {
            Mail::to($recipient)
                ->cc('noreply@satunusacapital.com')
                ->send(new ContactMessageReceived($message));
        } catch (\Throwable $error) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.']);
        }

        return back()->with('success', 'Pesan berhasil dikirim.');
    }
}
