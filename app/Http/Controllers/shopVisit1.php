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
        $district = $request->input('district');

        // ดึงข้อมูลร้านค้าที่อำเภอตรงกับคำค้น
        $shops = Shop::where('district', 'LIKE', "{$district} -%")->get();

        return response()->json($shops);
    }


}
