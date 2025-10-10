<?php

// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['lu' => true]);

        if ($notification->commande_id) {
            return redirect()->route('commandes.show', $notification->commande_id);
        }

        return back();
    }

    public function markAllAsRead()
    {
        $this->notificationService->marquerCommeLu(Auth::user());
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->where('lu', false)->count();
        return response()->json(['count' => $count]);
    }

}
