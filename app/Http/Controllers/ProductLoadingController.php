<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkRecord; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Warehouses; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Product; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Warehouse; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // นำเข้า DB เพื่อใช้ในการทำงานกับฐานข้อมูล
use App\Models\ProductLoading; // นำเข้าโมเดลที่ถูกต้อง
use App\Models\InventoryLoad; // นำเข้าโมเดลที่ถูกต้อง
use Carbon\Carbon;
use App\Models\Shop; // นำเข้าโมเดลที่ถูกต้อง
use App\Models\Category;

use App\Models\WorkRecordProduct; // นำเข้าโมเดลที่ถูกต้อง

class ProductLoadingController extends Controller
{

    public function index(Request $request)
    {    $user = Auth::user();
        // ดึงข้อมูลทั้งหมดจากตาราง product_loadings
        $query = ProductLoading::with(['product', 'workRecord'])->get();

        // ส่งข้อมูลไปยังวิว
        return view('product_loading.index', compact('query','user'));
    }

    // เพิ่มฟังก์ชันค้นหาที่นี่ (ถ้าต้องการ)
    public function search(Request $request)
    {
        // ดึงข้อมูลที่ค้นหาตามชื่อสินค้า
        $searchTerm = $request->input('search');
        $query = ProductLoading::with(['product', 'workRecord'])
            ->whereHas('product', function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%'); // เปลี่ยน 'name' เป็นฟิลด์ที่ต้องการค้นหา
            })
            ->get();

        return view('product_loading.index', compact('query'));
    }

    public function create($workRecordId)
    {    $user = Auth::user();
        $categories = Category::with('products')->get(); // ดึงหมวดหมู่พร้อมสินค้า

        // ดึงข้อมูลออเดอร์ที่ต้องทำการขนสินค้าขึ้นรถ
        //$workRecord = WorkRecord::with('products')->findOrFail($workRecordId);
        $workRecord = WorkRecord::with(['products', 'shop', 'user']) // โหลดความสัมพันธ์ products, shop และ user
        ->where('id', $workRecordId) // ใช้ 'id' เพื่อกรองเฉพาะออเดอร์ที่มี ID ตรงกับ $workRecordId
        ->first(); // ใช้ first() เพื่อดึงออเดอร์เดียวแทน get() ซึ่งจะดึงหลายออเดอร์


        // ดึงสินค้าที่มีทั้งหมดในระบบ
        $products = Product::all();

        // ดึงรถที่พร้อมสำหรับการขนส่งจากคลัง
        $vehicles = Warehouse::all(); // ในที่นี้ผมถือว่าคุณใช้ Warehouse เก็บข้อมูลรถ

        return view('product_loading.create', compact('workRecord', 'products', 'vehicles','user','categories'));
    }
    public function create1($shopId)
    {
        $user = Auth::user();

        // ดึงข้อมูลร้านค้า
        $shop = Shop::findOrFail($shopId);

        // ดึงสินค้าที่เกี่ยวข้องกับร้านค้า
        $products = Product::all();

        // ดึงรถที่พร้อมใช้งาน
        $vehicles = Warehouse::all(); // ในที่นี้ผมถือว่าคุณใช้ Warehouse เก็บข้อมูลรถ

        return view('product_loading.create', compact('shop', 'products', 'vehicles', 'user'));
    }


    public function viewCart()
    {
        $user = Auth::user();
        $userId = Auth::id(); // ดึง ID ของผู้ใช้งานที่ล็อกอินอยู่

        // ดึงสินค้าที่อยู่ในตะกร้า
        $cartItems = DB::table('work_record_product')
        ->join('products', 'work_record_product.product_id', '=', 'products.product_id')
        ->where('work_record_product.created_by', $userId)
        ->where('work_record_product.is_hidden', false) // เพิ่มเงื่อนไขที่นี่
        ->select('products.product_id', 'products.name', DB::raw('SUM(work_record_product.quantity) as total_quantity')) // รวมจำนวนสินค้าที่เหมือนกัน
        ->groupBy('products.product_id', 'products.name') // จัดกลุ่มตาม product_id และชื่อสินค้า
        ->get();


        $salesItems = DB::table('sales')
        ->leftJoin('products', 'sales.product_id', '=', 'products.product_id') // ใช้ leftJoin เพื่อให้รวมรายการที่ยังไม่มีการขาย
        ->where('sales.user_id', $userId)
        ->whereDate('sales.sale_date', Carbon::today()) // เพิ่มเงื่อนไขเพื่อกรองเฉพาะวันนี้
        ->select('products.product_id', 'products.name', DB::raw('SUM(sales.quantity) as total_sold')) // รวมจำนวนสินค้าที่ขาย
        ->groupBy('products.product_id', 'products.name') // จัดกลุ่มตาม product_id และชื่อสินค้า
        ->get()
        ->keyBy('name'); // แปลงเป็น key-value pair โดยใช้ 'name' เป็น key เพื่อให้ง่ายต่อการค้นหา
        // สร้าง array สำหรับข้อมูลสุดท้าย
        $finalItems = [];

        // รวมข้อมูลจาก cartItems และ salesItems
        foreach ($cartItems as $cartItem) {
            // หาจำนวนสินค้าที่ขายไปแล้วจากยอดขาย ถ้าไม่เจอจะให้ค่าเป็น 0
            $soldQuantity = $salesItems->get($cartItem->name)->total_sold ?? 0;
            $remainingQuantity = $cartItem->total_quantity - $soldQuantity; // หักลดจากจำนวนในตะกร้า

            $finalItems[] = [
                'product_id' => $cartItem->product_id, // ดึง product_id
                'name' => $cartItem->name,
                'total_quantity' => $cartItem->total_quantity,
                'total_sold' => $soldQuantity,
                'remaining_quantity' => $remainingQuantity,
            ];
        }

        // ส่งข้อมูลไปยังหน้า view
        return view('product_loading.cart', compact('finalItems', 'user'));
    }


    public function loadBackToWarehouse($productId)
    {
        // ดึงสินค้าที่อยู่ใน work_record_product ของ user ที่ล็อกอินและยังไม่ถูกโหลดกลับ (is_hidden = 0)
        $workRecordProducts = WorkRecordProduct::where('product_id', $productId)
                                                ->where('created_by', Auth::id())
                                                ->where('is_hidden', false)
                                                ->get();
        $userId = Auth::id(); // ดึง ID ของผู้ใช้งานที่ล็อกอินอยู่

        // ตรวจสอบว่ามีสินค้าที่ยังไม่ได้โหลดกลับหรือไม่
        if ($workRecordProducts->isEmpty()) {
            return redirect()->back()->withErrors(['message' => 'ไม่พบสินค้าที่ยังไม่ได้โหลดกลับ']);
        }

        // ทำการโหลดกลับคลัง
        foreach ($workRecordProducts as $product) {
            // บันทึกข้อมูลการโหลดใน inventory_loads
            InventoryLoad::create([
                'work_record_id' => $product->id,
                'product_id' => $product->product_id, // เพิ่ม product_id ที่นี่
                // ID ของ work_record_product
                'created_by' => $userId,
                'quantity' => $product->quantity,
                'warehouse_id' => 1, // หรือสามารถตั้งค่าให้เป็น dynamic ตามความต้องการ
            ]);

            // อัพเดทสถานะ is_hidden ใน work_record_product เป็น true (หรือ 1)
            $product->is_hidden = true;
            $product->save();
        }

        return redirect()->back()->with('success', 'สินค้าถูกโหลดกลับคลังเรียบร้อยแล้ว');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data


        // Retrieve the data from the request
        $productIds = $request->input('product_ids');
        $quantities = $request->input('quantities');
        $workRecordId = $request->input('work_record_id');

        // Create an array to hold the data to be inserted
        $dataToInsert = [];

        // Prepare data for insertion
        foreach ($productIds as $index => $productId) {
            $dataToInsert[] = [
                'work_record_id' => $workRecordId,
                'product_id' => $productId,
                'quantity' => $quantities[$index],
                'created_by' => Auth::id(), // ดึง ID ของผู้ที่ล็อกอิน
                'created_at' => now(),
                'updated_at' => now(),
                'is_hidden' => false, // แก้ไขเครื่องหมาย '=>' ที่นี่
            ];
        }

        // Insert data into the work_record_product table
        DB::table('work_record_product')->insert($dataToInsert);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'ออเดอร์ถูกบันทึกเรียบร้อยแล้ว!');
    }
    public function destroy($id)
    {
        $productLoading = ProductLoading::findOrFail($id);
        $productLoading->delete();

        return redirect()->route('product_loadings.index')->with('success', 'สินค้าถูกลบเรียบร้อยแล้ว');
    }


}
