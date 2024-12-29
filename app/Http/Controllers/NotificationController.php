<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // แสดงหน้าเพิ่มหรือแก้ไขการตั้งค่า API และ Token
    public function index()
    {    $user = Auth::user();
        $notification = Notification::first();
        return view('notifications.index', compact('notification','user'));
    }

    // บันทึกหรืออัปเดตการตั้งค่า API และ Token
    public function store(Request $request)
    {
        $request->validate([
            'line_api_url' => 'required|url',
            'token' => 'required|string',
        ]);

        $notification = Notification::firstOrCreate([]);
        $notification->update([
            'line_api_url' => $request->line_api_url,
            'token' => $request->token,
        ]);

        return redirect()->back()->with('success', 'อัปเดตการตั้งค่าการแจ้งเตือนสำเร็จ');
    }
}

