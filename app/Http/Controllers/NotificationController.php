<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi user (dengan pagination).
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Tandai notifikasi sebagai dibaca lalu redirect ke URL tujuan.
     */
    public function open($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        $url = data_get($notification->data, 'url', route('approval.index'));

        return redirect($url);
    }

    /**
     * Tandai semua notifikasi user sebagai dibaca.
     */
    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}