<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // ใช้โมเดลสำหรับดึงข้อมูลพนักงาน
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;

class EmployeeVisitController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        // ดึงข้อมูลพนักงานทั้งหมด
        $employees = User::where('status', 'ออนไลน์')->get();
        return view('employee-visit.index', compact('employees','user'));
    }
    public function show(Request $request)
    {
        // ดึงข้อมูลผู้ใช้ที่เข้าสู่ระบบ
        $user = Auth::user();

        // ดึงค่าจาก request
        $sales_rep_id = $request->input('sales_rep_id');
        $visit_date = $request->input('visit_date');


        // เช็คค่าที่รับเข้ามา
        if (!$sales_rep_id || !$visit_date) {
            return redirect()->back()->with('error', 'กรุณากรอกข้อมูลให้ครบถ้วน');
        }

        // ค้นหาข้อมูลการเดินทางของพนักงาน (Trip) ในวันที่เลือก
        $trips = Trip::where('sales_rep_id', $sales_rep_id)
                     ->whereDate('time', $visit_date)
                     ->get();

        // เช็คว่ามีข้อมูล trips หรือไม่
        if ($trips->isEmpty()) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลการเดินทางสำหรับพนักงานและวันที่ที่เลือก');
        }

        // ส่งข้อมูลไปยังหน้าจะแสดงแผนที่
        return view('employee-visit.show', compact('trips', 'user'));
    }


}
