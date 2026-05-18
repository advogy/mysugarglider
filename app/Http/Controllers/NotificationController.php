<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->take(30)->get()->map(fn ($n) => [
            'id'    => $n->id,
            'title' => $n->data['title'] ?? '',
            'body'  => $n->data['body']  ?? '',
            'url'   => $n->data['url']   ?? '#',
            'icon'  => $n->data['icon']  ?? 'bi-bell',
            'read'  => !is_null($n->read_at),
            'time'  => $n->created_at->diffForHumans(),
        ]);

        return response()->json([
            'unread_count'  => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['ok' => true]);
    }
}
