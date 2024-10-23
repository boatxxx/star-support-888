<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Shop; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\User; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Promotion; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
 // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\PromotionCondition; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\PromotionDiscount; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\PromotionProduct; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Sale; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

use App\Models\WorkRecord; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\WorkRecordItem; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\WorkRecordProduct; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

class QuotationController extends Controller
{
    public function show($id)
    {    $user = Auth::user();
        $products = Product::all();
        $promotions = Promotion::all();
        // ดึงข้อมูลออเดอร์ที่ต้องการ

        $workRecord = WorkRecord::findOrFail($id);
        return view('quotation.show', compact('workRecord','user','products','promotions'));
    }



    public function store(Request $request, $id)
    {
        $user = Auth::user();
        $workRecord = WorkRecord::findOrFail($id);

        // รับข้อมูลสินค้าที่ส่งมา
        $items = $request->input('items');
        $selectedPromotionId = $request->input('selected_promotion');

        // รับ ID ของสินค้าที่เกี่ยวข้อง
        $productIds = array_column($items, 'product_id');

        // ดึงข้อมูลสินค้าที่เกี่ยวข้องเท่านั้น
        $products = Product::whereIn('product_id', $productIds)->get()->keyBy('product_id');
        $userId = Auth::id(); // ID ของผู้ใช้งาน

        // เริ่มต้นยอดรวม
        $total = 0;

        // ดึงรายการสินค้าในตะกร้า
        $cartItems = WorkRecordProduct::where('created_by', $userId)
        ->where('is_hidden', false) // เพิ่มเงื่อนไขเพื่อกรองเฉพาะสินค้าที่ยังไม่ถูกซ่อน
        ->select('product_id', DB::raw('SUM(quantity) as total_quantity')) // รวมยอดจำนวนสินค้า
        ->groupBy('product_id') // จัดกลุ่มตาม product_id
        ->get()
        ->keyBy('product_id'); // แปลงผลลัพธ์เป็น key-value pair โดยใช้ 'product_id' เป็น key


        // เช็คว่ามีสินค้าหรือไม่ และจำนวนเพียงพอหรือไม่
        foreach ($items as $item) {
            $productId = $item['product_id'];
            $requestedQuantity = $item['quantity'];

            // เช็คว่าในตะกร้ามีสินค้านี้หรือไม่
            if ($cartItems->has($productId)) {
                // หากมีสินค้าในตะกร้า
                $cartQuantity = $cartItems[$productId]->total_quantity; // ใช้ total_quantity ที่รวมมาแล้ว

                // ดึงยอดขายที่มีอยู่แล้ว
                $soldQuantity = Sale::where('product_id', $productId)
                                    ->where('user_id', $userId)
                                    ->sum('quantity');

                // คำนวณจำนวนที่สามารถขายได้
                $availableQuantity = $cartQuantity - $soldQuantity;

                // ตรวจสอบจำนวน
                if ($requestedQuantity > $availableQuantity) {
                    // จำนวนไม่เพียงพอ
                    return redirect()->back()->withErrors(["$productId: จำนวนสินค้าในตะกร้าไม่เพียงพอ"]);
                }
            } else {
                // ไม่มีสินค้านี้ในตะกร้า
                return redirect()->back()->withErrors(["$productId: สินค้าไม่มีในตะกร้า"]);
            }
        }

        // ตรวจสอบว่ามีการเลือกโปรโมชั่นหรือไม่
        if ($selectedPromotionId) {
            // ดึงโปรโมชั่นที่เลือก
            $promotion = Promotion::with(['discounts', 'conditions'])->findOrFail($selectedPromotionId);

            // ถ้าโปรโมชั่นเป็นแบบ percentage_discount
            if ($promotion->type === 'percentage_discount') {
                $percentageDiscount = $promotion->discounts->firstWhere('discount_type', 'percentage');

                if ($percentageDiscount) {
                    foreach ($items as &$item) {
                        $itemPrice = $item['price'];
                        $item['price'] = $itemPrice * (1 - ($percentageDiscount->discount_value / 100)); // ลดราคา
                        $total += $item['quantity'] * $item['price'];
                    }
                }
            }

            // ถ้าโปรโมชั่นเป็นแบบ product_specific_discount
            if ($promotion->type === 'product_specific_discount') {
                $condition = PromotionCondition::where('promotion_id', $promotion->promotion_id)->first();

                if ($condition) {
                    $conditionValue = $condition->condition_value;

                    foreach ($items as &$item) {
                        $item['price'] -= $conditionValue; // ลบค่าที่กำหนดใน condition_value
                        if ($item['price'] < 0) {
                            $item['price'] = 0; // ไม่ให้ราคาติดลบ
                        }
                        $total += $item['quantity'] * $item['price'];
                    }
                }
            }
        } else {
            foreach ($items as $item) {
                $total += $item['quantity'] * $item['price'];
            }
        }

        // สร้างรายการขาย


        // ส่งข้อมูลไปยังหน้าใบเสนอราคา
        return view('quotation.summary', compact('items', 'total', 'products', 'user', 'workRecord'));
    }








}
