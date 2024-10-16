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

        // ดึงข้อมูลออเดอร์ที่ต้องทำการขนสินค้าขึ้นรถ
        //$workRecord = WorkRecord::with('products')->findOrFail($workRecordId);
        $workRecord = WorkRecord::with(['products', 'shop', 'user']) // โหลดความสัมพันธ์ products, shop และ user
        ->where('id', $workRecordId) // ใช้ 'id' เพื่อกรองเฉพาะออเดอร์ที่มี ID ตรงกับ $workRecordId
        ->first(); // ใช้ first() เพื่อดึงออเดอร์เดียวแทน get() ซึ่งจะดึงหลายออเดอร์


        // ดึงสินค้าที่มีทั้งหมดในระบบ
        $products = Product::all();

        // ดึงรถที่พร้อมสำหรับการขนส่งจากคลัง
        $vehicles = Warehouse::all(); // ในที่นี้ผมถือว่าคุณใช้ Warehouse เก็บข้อมูลรถ

        return view('product_loading.create', compact('workRecord', 'products', 'vehicles','user'));
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
            ];
        }

        // Insert data into the work_record_product table
        DB::table('work_record_product')->insert($dataToInsert);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'ออเดอร์ถูกบันทึกเรียบร้อยแล้ว!');
    }



}
