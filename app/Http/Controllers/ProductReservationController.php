<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Shop; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

use App\Models\WorkRecord; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

class ProductReservationController extends Controller
{
    public function create($workRecordId) // รับค่า ID ที่ส่งมาจาก URL
    {
        $user = Auth::user(); // ดึงข้อมูลผู้ใช้งานที่ล็อกอินอยู่
        $products = Product::all(); // ดึงสินค้าทั้งหมด

        // ดึงข้อมูล WorkRecord และ shop ที่เกี่ยวข้อง
        $workRecord = WorkRecord::with('shop')->findOrFail($workRecordId); // ใช้ $workRecordId แทน $id
        $shops = Shop::all(); // ดึงข้อมูลร้านค้าทั้งหมด

        return view('product_reservation.create', compact('products', 'user', 'workRecord', 'shops')); // ส่งข้อมูลไปยัง view
    }

    public function create1($shopId) // รับค่า shopId จาก URL
    {
        $user = Auth::user(); // ดึงข้อมูลผู้ใช้งานที่ล็อกอินอยู่
        $products = Product::all(); // ดึงสินค้าทั้งหมด
        // ใช้เพื่อดีบั๊กและตรวจสอบค่าที่ส่งมา

        // ดึงข้อมูลร้านค้าที่เกี่ยวข้องกับ shopId
        $shop = Shop::where('shop_id', $shopId)->firstOrFail();
        $shops = Shop::all(); // ดึงข้อมูลร้านค้าทั้งหมด

        return view('product_reservation.create', compact('products', 'user', 'shop', 'shops')); // ส่งข้อมูลไปยัง view
    }

 public function index(Request $request)
 {
    $user = Auth::user(); // ดึงข้อมูลผู้ใช้งานที่ล็อกอินอยู่
        // รับค่าค้นหาจาก request
        $searchTerm = $request->input('search');

        // เรียกข้อมูลประวัติการจอง พร้อมโหลดข้อมูลสินค้าที่เกี่ยวข้องและร้านค้า
        $reservations = Reservation::with(['product', 'shop', 'user'])
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->whereHas('product', function ($query) use ($searchTerm) {
                    // ค้นหาจากชื่อสินค้า
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('shop', function ($query) use ($searchTerm) {
                    // ค้นหาจากชื่อร้านค้า
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
            })
            ->get();

        // ส่งข้อมูลไปยังวิว
        return view('product_reservation.index', compact('reservations', 'searchTerm','user'));
    }
    public function store(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่ส่งมา
        $validatedData = $request->validate([
            'product_ids' => 'required|array', // ค่าต้องเป็น array
            'quantities' => 'required|array', // ค่าต้องเป็น array
            'quantities.*' => 'required|integer|min:1', // จำนวนต้องเป็นจำนวนเต็มมากกว่าหรือเท่ากับ 1
            'shop_id' => 'required|exists:shops,shop_id', // ตรวจสอบให้แน่ใจว่า shop_id ต้องมีอยู่ในตาราง shops
        ]);

        foreach ($validatedData['product_ids'] as $index => $productId) {
            $quantity = $validatedData['quantities'][$index];

            try {
                // สร้างข้อมูลการจองใหม่
                Reservation::create([
                    'product_id' => $productId,
                    'user_id' => Auth::id(),
                    'shop_id' => $validatedData['shop_id'],
                    'quantity' => $quantity,
                    'reservation_date' => now(),
                ]);
            } catch (\Exception $e) {
                // แสดงข้อความข้อผิดพลาด
                return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage());
            }
        }

        // ส่งข้อความยืนยันกลับไป
        return redirect()->back()->with('success', 'การจองสินค้าสำเร็จ!');
    }

}
