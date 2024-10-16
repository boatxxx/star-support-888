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

use App\Models\WorkRecord; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\WorkRecordItem; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

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

        // เริ่มต้นยอดรวม
        $total = 0;

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

        // ส่งข้อมูลไปยังหน้าใบเสนอราคา
        return view('quotation.summary', compact('items', 'total', 'products', 'user','workRecord'));
    }







}
