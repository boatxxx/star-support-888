<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TripsController extends Controller
{
    public function updatePosition(Request $request)
    {
        $userId = Auth::id(); // ดึง ID ของผู้ใช้งานที่ล็อกอิน

        // Validate ข้อมูลที่รับมา
        $request->validate([
            'latitude' => 'required|numeric',  // ตรวจสอบว่า latitude เป็นตัวเลข
            'longitude' => 'required|numeric', // ตรวจสอบว่า longitude เป็นตัวเลข
        ]);

        // สร้างข้อมูลใหม่ในตาราง trips
        $trip = new Trip();
        $trip->sales_rep_id = $userId;            // กำหนด ID ของผู้แทนขาย
        $trip->latitude_date = $request->latitude;  // ใช้ชื่อฟิลด์ที่ตรงกับฐานข้อมูล
        $trip->longitude_date = $request->longitude; // ใช้ชื่อฟิลด์ที่ตรงกับฐานข้อมูล
        $trip->time = now(); // กำหนดเวลาปัจจุบัน

        // บันทึกข้อมูลลงในฐานข้อมูล
        $trip->save();

        // ส่งข้อความตอบกลับเป็น JSON
        return response()->json(['success' => 'Position updated successfully']);
    }
    public function showMap(Request $request)
    {
        $user = Auth::user();
        $selectedSalesRepId = $request->input('sales_rep_id');
        $selectedDate = $request->input('date');

        // สร้าง Query และกรองข้อมูลตามตัวเลือก
        $workHistories = Trip::when($selectedSalesRepId, function ($query, $id) {
                return $query->where('sales_rep_id', $id);
            })
            ->when($selectedDate, function ($query, $date) {
                return $query->whereDate('created_at', $date);
            })
            ->get();

        // ดึงเฉพาะพนักงานที่มีประวัติการเดินทาง
        $salesReps = User::whereIn('user_id', Trip::distinct()->pluck('sales_rep_id'))->get();

        return response()->json([
            'workHistories' => $workHistories,
            'salesReps' => $salesReps
        ]);
    }





}
