<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

use App\Models\Role; // ตรวจสอบการนำเข้าคลาส Role

class UserController extends Controller
{
    public function create()
    {    $user = Auth::user();
        return view('create', compact('user'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id', // ตรวจสอบว่า role_id มีในตาราง roles
            'status' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'img_user' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->status = $request->status;
        $user->password = bcrypt($request->password);

        // อัพโหลดรูปภาพถ้ามี
        if ($request->hasFile('img_user')) {
            $imageName = time().'.'.$request->img_user->extension();
            $request->img_user->move(public_path('storage'), $imageName);
            $user->img_user = $imageName;
        }

        $user->save();
        $message = "พนักงานใหม่เพิ่มเข้าสู่ระบบ!\n" .
        "ชื่อพนักงาน: {$user->name}\n" .
        "อีเมล: {$user->email}\n";

    $isSent = Notification::sendLineNotification($message);
        return redirect()->route('users.create')->with('success', 'User created successfully.');
    }
    public function show($id)
{

    $user = Auth::user();
    $employee = User::findOrFail($id);
    return view('employees.show', compact('employee','user'));
}
// app/Http/Controllers/UserController.php
public function edit($id)
{    $user = Auth::user();
    $employee = User::findOrFail($id);
    return view('employees.edit', compact('employee','user'));
}
public function update(Request $request, $id)
{
    // Find the user by ID or fail if not found
    $user = User::findOrFail($id);

    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id . ',user_id',
        'role_id' => 'required|integer',
        'status' => 'required|string|max:255',
        'img_user' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Update user attributes
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->role_id = $request->input('role_id');
    $user->status = $request->input('status');
    $user->user_id = $request->input('user_id');
    $user->password = $request->input('password');
    // Check if a new image file is uploaded
    if ($request->hasFile('img_user')) {
        // Remove old image if it exists
        if ($user->img_user && file_exists(public_path('storage/' . $user->img_user))) {
            unlink(public_path('storage/' . $user->img_user));
        }

        // Upload new image
        $imageName = time().'.'.$request->img_user->extension();
        $request->img_user->move(public_path('storage'), $imageName);
        $user->img_user = $imageName;
    }

    // Save the updated user information
    $user->save();
    $user1 = Auth::User();
    $userName = $user1->name;
    $message = "แก้ไขข้อมูลพนักงาน!\n" .
    "ชื่อพนักงาน: {$user->name}\n" ."แก้ไขโดยพนักงานชื่อ $userName";

$isSent = Notification::sendLineNotification($message);
    // Redirect to the index page with a success message
    return redirect()->route('employees.index')->with('success', 'User updated successfully');
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();
    $user1 = Auth::User();
    $userName = $user1->name;
    $message = "ลบพนักงานออกจากระบบ!\n" .
    "ชื่อพนักงาน: {$user->name}\n" ."แก้ไขโดยพนักงานชื่อ $userName";

$isSent = Notification::sendLineNotification($message);
    return redirect()->route('employees.index')->with('success', 'User deleted successfully');
}

}
