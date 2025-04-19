<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{

    public function index()
    {

        $user = Auth::user();
        $employees = User::where('status', '!=', 'deleted')->get(); // ดึงข้อมูลพนักงานที่ไม่ถูกลบ
        return view('employees.index', compact('employees', 'user'));
    }
}
