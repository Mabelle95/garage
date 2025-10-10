<?php


// app/Http/Controllers/MessageController.php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Message::where('destinataire_id', Auth::id())
            ->orWhere('expediteur_id', Auth::id())
            ->with(['expediteur', 'destinataire'])
            ->latest()
            ->paginate(20);

        return view('messages.index', compact('conversations'));
    }

    public function create(User $destinataire)
    {
        return view('messages.create', compact('destinataire'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:users,id',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $request->destinataire_id,
            'sujet' => $request->sujet,
            'message' => $request->message
        ]);

        return redirect()->route('messages.show', $message)
            ->with('success', 'Message envoyé avec succès.');
    }

    public function show(Message $message)
    {
        if ($message->destinataire_id !== Auth::id() && $message->expediteur_id !== Auth::id()) {
            abort(403);
        }

        if ($message->destinataire_id === Auth::id() && !$message->lu) {
            $message->update(['lu' => true]);
        }

        return view('messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $reply = Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $message->expediteur_id === Auth::id() ?
                $message->destinataire_id : $message->expediteur_id,
            'sujet' => 'Re: ' . $message->sujet,
            'message' => $request->message
        ]);

        return back()->with('success', 'Réponse envoyée.');
    }
}
