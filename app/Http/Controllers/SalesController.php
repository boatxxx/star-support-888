<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{

    public function salesByShop(Request $request)
    { $user = Auth::user();
        $salesQuery = Sale::with(['shop', 'user']);

        // ตรวจสอบวันที่ค้นหา
        if ($request->has('start_date') && $request->has('end_date')) {
            $salesQuery->whereBetween('sale_date', [$request->start_date, $request->end_date]);
        }

        // ดึงข้อมูลยอดขาย
        $sales = $salesQuery->get();

        return view('sales.sales_by_shop', compact('sales','user'));
    }

    // ฟังก์ชันสำหรับแสดงยอดขายของพนักงาน
    public function salesByEmployee(Request $request)
    { $user = Auth::user();
        $salesQuery = Sale::with(['user']);

        // ตรวจสอบวันที่ค้นหา
        if ($request->has('start_date') && $request->has('end_date')) {
            $salesQuery->whereBetween('sale_date', [$request->start_date, $request->end_date]);
        }

        // ดึงข้อมูลยอดขาย
        $sales = $salesQuery->get();

        return view('sales.sales_by_employee', compact('sales','user'));
    }
    public function index(Request $request)
{
    $user = Auth::user();
    // รับค่าค้นหาจากฟอร์ม
    $search = $request->input('search'); // ค้นหาจากชื่อสินค้า หรือ ID สินค้า

    // เรียกข้อมูลจากตาราง Sale พร้อมความสัมพันธ์กับ product และ shop
    $query = Sale::with(['product', 'shop', 'user'])
                ->when($search, function($q) use ($search) {
                    // ค้นหาจากชื่อสินค้าหรือรหัสสินค้า
                    $q->whereHas('product', function($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%")
                          ->orWhere('product_id', 'LIKE', "%$search%");
                    });
                })
                ->orderBy('sale_date', 'desc') // เรียงตามวันที่ขาย
                ->get();

    return view('sales.index', compact('query','user')); // ส่งข้อมูลไปยังวิว
}
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|json',
            'shop_id' => 'required|exists:shops,shop_id', // ตรวจสอบว่ามี shop_id ที่ถูกต้อง
        ]);
        // รับข้อมูลสินค้าที่ส่งมา
        $items = json_decode($request->input('items'), true); // แปลง JSON เป็น Array

        // เริ่มต้นยอดรวม
        $total = 0;

        // บันทึกข้อมูลการขาย
        foreach ($items as $item) {
            // คำนวณราคาสินค้า
            $itemTotal = $item['quantity'] * $item['price'];
            $total += $itemTotal;

            // สร้างข้อมูลการขายในฐานข้อมูล
            Sale::create([
                'shop_id' => $request->input('shop_id'), // คุณสามารถเปลี่ยนเป็น ID ของร้านค้าที่ต้องการ
                'product_id' => $item['product_id'],
                'total_price' => $itemTotal,
                'sale_date' => now(),  // วันที่ขาย
                'user_id' => Auth::id(), // ID ของผู้ใช้งาน
                'promotion_id' => 12,
            ]);
        }

        // ส่งข้อมูลกลับไปยังหน้าแรก
        return redirect('user');
    }
}
