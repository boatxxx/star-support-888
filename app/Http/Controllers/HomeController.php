<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Sale; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\WorkRecord; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

use App\Models\Shop; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        // ดึงข้อมูลพนักงานทั้งหมด
        $employeesCount = User::where('status', 'ออนไลน์')->count();

        // ดึงข้อมูลยอดขายทั้งหมด
        $totalSales = Sale::sum('total_price');

        // ดึงข้อมูลยอดขายในช่วงเวลาที่กำหนด
        $salesToday = Sale::whereDate('sale_date', today())->sum('total_price');
        $sales = Sale::with(['user', 'shop'])->get(); // โหลดข้อมูลยอดขายพร้อมกับข้อมูลพนักงานและร้านค้า

        // ดึงข้อมูลลูกค้าทั้งหมด (นับจำนวนร้านค้า)
        $customersCount = Shop::count();
        $shops = Shop::all(); // หากต้องการดึงข้อมูลร้านค้าเพื่อนำไปใช้ในหน้าแดชบอร์ด

        // ดึงข้อมูลการขายล่าสุด (5 รายการ)
        $recentSales = Sale::latest()->take(5)->get();

        // หากมีการค้นหายอดขายจากร้านค้า
        $salesByShop = collect();
        if ($request->has('start_date') && $request->has('end_date')) {
            $salesByShop = Sale::with(['shop', 'user'])
                ->whereBetween('sale_date', [$request->start_date, $request->end_date])
                ->get();
        }

        // หากมีการค้นหายอดขายจากพนักงาน
        $salesByEmployee = collect();
        if ($request->has('start_date') && $request->has('end_date')) {
            $salesByEmployee = Sale::with(['user'])
                ->whereBetween('sale_date', [$request->start_date, $request->end_date])
                ->get();
        }

        return view('admin', compact(
            'employeesCount',
            'totalSales',
            'sales',
            'salesToday',
            'customersCount',
            'recentSales',
            'user',
            'shops',
            'salesByShop',
            'salesByEmployee'
        ));
    }

    public function index1()
    {
        $completedOrders = WorkRecord::where('status', 'completed')->count();
        $pendingOrders = WorkRecord::where('status', 'pending')->count();
        $user = Auth::user();
        return view('user', compact('user','completedOrders', 'pendingOrders'));
    }
}
