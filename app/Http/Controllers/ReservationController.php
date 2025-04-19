<?php

namespace App\Http\Controllers;
use App\Models\Product; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function create($shop_id)
    {
        // ดึงข้อมูลสินค้าทั้งหมด
        $products = Product::all();
        $user = Auth::user();

        // ส่งข้อมูลสินค้าทั้งหมดและ shop_id ไปยังมุมมอง
        return view('reservations.create', compact('products', 'user', 'shop_id'));
    }


    public function store(Request $request, $shop_id)
    {
        // ตรวจสอบความถูกต้องของข้อมูล
        $validatedData = $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
            'reservation_date' => 'required|array',
            'reservation_date.*' => 'date',
        ]);

        foreach ($validatedData['product_id'] as $index => $productId) {
            Reservation::create([
                'product_id' => $productId,
                'user_id' => Auth::id(), // ใช้ Auth::id() เพื่อรับ user_id
                'shop_id' => $shop_id,
                'quantity' => $validatedData['quantity'][$index],
                'reservation_date' => $validatedData['reservation_date'][$index],
            ]);
        }

        // เปลี่ยนเส้นทางไปยังหน้าหลักพร้อมกับข้อความแสดงความสำเร็จ
        return redirect()->route('reservations.create', ['shop_id' => $shop_id])
                         ->with('success', 'จองสินค้าเรียบร้อยแล้ว');
    }

}
