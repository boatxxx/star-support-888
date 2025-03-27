<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopVisit;
use App\Models\CustomerVisit;


class shopVisit1 extends Controller
{
    public function createuser()
    {
        $user = Auth::user();
        $employees = User::all();

        return view('shop_visits.create', compact('employees', 'user'));
    }

    // เพิ่มฟังก์ชันสำหรับดึงร้านค้าตามอำเภอ
    public function getShopsByDistrict(Request $request)
{
    $district = $request->input('district'); // อำเภอ เช่น "บ้านค่าย"
    $subdistrict = $request->input('subdistrict'); // ตำบล เช่น "หนองละลอก"

    // ตรวจสอบว่ามีค่าอำเภอและตำบลหรือไม่
    if (!$district || !$subdistrict) {
        return response()->json(['error' => 'กรุณาเลือกอำเภอและตำบล'], 400);
    }

    // ใช้ LIKE เพื่อค้นหาอำเภอและตำบลที่ถูกต้อง
    $shops = Shop::where('district', 'LIKE', "{$district} - {$subdistrict}")
                 ->get();

    return response()->json($shops);
}



    }



