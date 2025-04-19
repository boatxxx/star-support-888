<?php

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController   extends Controller
{
    public function store(Request $request)
    {
        // การตรวจสอบข้อมูลที่ได้รับจากแบบฟอร์ม
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ตรวจสอบการเข้าสู่ระบบของผู้ใช้
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $user->status = 'ออนไลน์';
            $user->save();

            // รีเซ็ตเซสชันเพื่อป้องกันการโจมตี CSRF
            $request->session()->regenerate();

            // เปลี่ยนเส้นทางไปยังหน้าที่เหมาะสม
            return redirect()->intended($user->role_id == 1 ? '/admin' : '/user');
        }

        // ส่งกลับข้อผิดพลาดหากข้อมูลรับรองไม่ถูกต้อง
        return back()->withErrors([
            'email' => 'ข้อมูลรับรองที่ให้มามิได้ตรงกับข้อมูลของเรา',
        ]);
    }
    public function destroy(Request $request)
{
    $user = Auth::user();
    $user->status = 'ออฟไลน์';
    $user->save();

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}

}
