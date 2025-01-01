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
use App\Models\Category;

class QuotationController extends Controller
{
    public function show($id = null, $shopId = null)
    {
        $user = Auth::user();
        $products = Product::all();
        $promotions = Promotion::with(['conditions', 'discounts', 'products'])->where('status', 'active')->get();
        $categories = Category::with('products')->get(); // ดึงหมวดหมู่พร้อมสินค้า

        // หากมี shop_id จะใช้ข้อมูลร้านค้า
       // ตรวจสอบการหาค่าของ $shop จาก $shop_id
       $shop = Shop::find($shopId);

// ตรวจสอบว่า $shop มีค่า
$shopName = $shop ? $shop->name : 'ไม่พบข้อมูลร้านค้า';

// หากไม่พบร้านค้า (เมื่อ $shop เป็น null) จะให้ข้อความว่า 'ไม่พบข้อมูลร้านค้า'

        if ($shopId && !$shop) {
            return redirect()->back()->withErrors(['error' => 'ร้านค้าที่ส่งมาไม่พบ.']);
        }

        // ถ้ามี id (ออเดอร์ที่มีอยู่แล้ว) จะดึงข้อมูลจาก WorkRecord
        $workRecord = $id ? WorkRecord::with(['items.product', 'shop'])->find($id) : null;

        // หากไม่มี workRecord (ไม่พบออเดอร์ที่มีอยู่) ให้สร้าง WorkRecord ใหม่
        if (!$workRecord && $shop) {
            $workRecord = new WorkRecord();
            $workRecord->shop_id = $shop->id;
            $workRecord->order_date = now();
            // สร้างเลขออเดอร์ใหม่จากร้านค้า
            $workRecord->id = WorkRecord::where('shop_id', $shop->id)->max('id') + 1;
            $workRecord->save();
        }

        // ส่งค่าต่าง ๆ ไปยัง view
        return view('quotation.show', compact('user', 'shop', 'shopName', 'products', 'promotions', 'workRecord','categories'));
    }


    public function show1($shopId = null, $id = null)
    {
        // รับข้อมูลผู้ใช้ที่ล็อกอิน
        $user = Auth::user();

        // ดึงข้อมูลสินค้าและโปรโมชั่นทั้งหมด
        $products = Product::all();
        $promotions = Promotion::with(['conditions', 'discounts', 'products'])->where('status', 'active')->get();

        // ตรวจสอบว่ามี shopId หรือไม่ ถ้ามีให้ดึงข้อมูลร้านค้า
        if ($shopId) {
            // ดึงข้อมูลร้านค้าตาม shopId
            $shop = Shop::find($shopId);

            // หากไม่พบร้านค้า (เมื่อ $shop เป็น null) ให้แสดงข้อความ error
            if (!$shop) {
                return redirect()->back()->withErrors(['error' => 'ร้านค้าที่ส่งมาไม่พบ.']);
            }

            // กำหนดชื่อร้านค้า
            $shopName = $shop->name;
        } else {
            // ถ้าไม่มี shopId ให้กำหนดค่า $shop เป็น null และชื่อร้านค้าเป็นค่าว่าง
            $shop = null;
            $shopName = null;
        }

        // ถ้ามี id (ออเดอร์ที่มีอยู่แล้ว) จะดึงข้อมูลจาก WorkRecord
        $workRecord = $id ? WorkRecord::with(['items.product', 'shop'])->find($id) : null;

        // หากไม่มี workRecord (ไม่พบออเดอร์ที่มีอยู่) และ $shop มีค่า ให้สร้าง WorkRecord ใหม่
        if (!$workRecord && $shop) {
            // ตรวจสอบว่า shop_id มีค่าไหมก่อนสร้าง WorkRecord
            $workRecord = new WorkRecord();
            $workRecord->shop_id = $shopId;  // ใช้ shop_id ที่ดึงมาโดยตรง
            $workRecord->order_date = now();

            // ไม่กำหนด id ด้วยตัวเอง ให้ฐานข้อมูลสร้าง id ใหม่ให้โดยอัตโนมัติ
            // $workRecord->id = WorkRecord::where('shop_id', $shop->id)->max('id') + 1;

            // กำหนดค่า user_id จากผู้ใช้ที่ล็อกอิน
            $workRecord->user_id = $user->user_id;

            // กำหนดค่า status ให้เป็น 'กำลังดำเนินการ'
            $workRecord->status = 'กำลังดำเนินการ';

            // บันทึก WorkRecord ใหม่
            $workRecord->save();
        }

        // ส่งค่าต่าง ๆ ไปยัง view
        return view('quotation.show', compact('user', 'shop', 'shopName', 'products', 'promotions', 'workRecord'));
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
