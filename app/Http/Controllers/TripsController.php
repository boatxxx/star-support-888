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
        // ดึงข้อมูลประวัติการทำงานทั้งหมด
        $workHistories = Trip::with('salesRep');

        // ดึง ID ของพนักงานที่เลือก (ถ้ามี)
        $selectedSalesRepId = $request->input('sales_rep_id');
        // ดึงวันที่ที่เลือก (ถ้ามี)
        $selectedDate = $request->input('date');

        // หากมีการเลือกพนักงาน ให้ดึงข้อมูลประวัติการทำงานของพนักงานที่เลือก
        if ($selectedSalesRepId) {
            $workHistories = $workHistories->where('sales_rep_id', $selectedSalesRepId);
        }

        // หากมีการเลือกวันที่ ให้ดึงข้อมูลในวันที่นั้น
        if ($selectedDate) {
            $workHistories = $workHistories->whereDate('created_at', $selectedDate);
        }

        // สุดท้าย ดึงข้อมูลที่ต้องการ
        $workHistories = $workHistories->get();

        // ดึงรายการพนักงานทั้งหมดสำหรับการเลือก
        $salesReps = User::all();

        return view('trips.map', compact('workHistories', 'user', 'selectedSalesRepId', 'selectedDate', 'salesReps'));
    }


}
