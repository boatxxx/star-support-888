<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Shop; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\User; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\WorkRecord; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\WorkRecordItem; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

class WorkRecordController extends Controller
{  public function index()
    {    $user = Auth::user();
        $workRecords = WorkRecord::all(); // ดึงข้อมูลทั้งหมดจากตาราง work_records
        return view('work_records.index', compact('workRecords','user'));
    }
    public function show($id)
    {    $user = Auth::user();

        $workRecord = WorkRecord::with('shop', 'user', 'items.product')->findOrFail($id);

        return view('work_records.show', compact('workRecord','user'));
    }

    public function destroy($id)
    {
        try {
            // Find the WorkRecord and delete it
            $workRecord = WorkRecord::findOrFail($id);
            $workRecord->delete();

            // Redirect with success message
            return redirect()->route('work_records.index')->with('success', 'ออเดอร์ถูกลบแล้ว');
        } catch (\Exception $e) {
            // Redirect with error message if something goes wrong
            return redirect()->back()->with('error', 'ไม่สามารถลบออเดอร์ได้: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {    $user = Auth::user();

    $workRecord = WorkRecord::with('items.product')->findOrFail($id); // Load related items and products
    $products = Product::all(); // Get all products for the dropdown
    $shops = Shop::all(); // Get all shops for the dropdown
    $users = User::all(); // Get all users for the dropdown

    return view('work_records.edit', compact('workRecord', 'products', 'shops', 'users','user'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'product_ids' => 'required|array',
        'quantities' => 'required|array',
        'order_date' => 'required|date',
        'description' => 'nullable|string',
        'shop_id' => 'required',
        'user_id' => 'required',
        'status' => 'required|in:pending,completed,cancelled',
    ]);

    DB::beginTransaction();

    try {
        // Update the WorkRecord
        $workRecord = WorkRecord::findOrFail($id);
        $workRecord->update([
            'order_date' => $request->input('order_date'),
            'description' => $request->input('description'),
            'shop_id' => $request->input('shop_id'),
            'user_id' => $request->input('user_id'),
            'status' => $request->input('status'),
        ]);

        // Update WorkRecordItems
        $workRecord->items()->delete(); // Remove existing items
        foreach ($request->input('product_ids') as $index => $product_id) {
            WorkRecordItem::create([
                'work_record_id' => $workRecord->id,
                'product_id' => $product_id,
                'quantity' => $request->input('quantities')[$index],
            ]);
        }

        DB::commit();

        return redirect()->route('work_records.index')->with('success', 'ออเดอร์ถูกอัพเดตแล้ว');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->with('error', 'ไม่สามารถอัพเดตออเดอร์ได้: ' . $e->getMessage());
    }
}

public function review()
{
    $user = Auth::user();

    // ดึงข้อมูลออเดอร์ทั้งหมดที่เป็นของผู้ใช้ที่ล็อกอิน (กรองด้วย user_id)
    $workRecords = WorkRecord::with(['items.product', 'shop', 'user'])
        ->where('user_id', $user->user_id)  // กรองเฉพาะออเดอร์ที่ user_id ตรงกับผู้ล็อกอิน
        ->get();

    return view('work_records.review', compact('workRecords', 'user'));
}

    public function create()
    {
        // ดึงข้อมูลสินค้า, ร้านค้า, และยูเซอร์
        $products = Product::all();
        $shops = Shop::all();
        $users = User::all();

        // ส่งข้อมูลไปยัง view
        return view('work_records.create', compact('products', 'shops', 'users'));
    }
   // Controller - WorkRecordController.php
   public function store(Request $request)
   {
       $request->validate([
           'product_ids' => 'required|array',
           'quantities' => 'required|array',
           'order_date' => 'required|date',
           'description' => 'nullable|string',
           'shop_id' => 'required',
           'user_id' => 'required',
           'status' => 'required|in:pending,completed,cancelled',
       ]);

       DB::beginTransaction();

       try {
           // สร้างบันทึก WorkRecord
           $workRecord = WorkRecord::create([
               'order_date' => $request->input('order_date'),
               'description' => $request->input('description'),
               'shop_id' => $request->input('shop_id'),
               'user_id' => $request->input('user_id'),
               'status' => $request->input('status'),
           ]);

           // สร้าง WorkRecordItems ที่เชื่อมโยงกับ WorkRecord
           foreach ($request->input('product_ids') as $index => $product_id) {
               WorkRecordItem::create([
                   'work_record_id' => $workRecord->id,
                   'product_id' => $product_id,
                   'quantity' => $request->input('quantities')[$index],
               ]);
           }

           DB::commit();

           return redirect()->route('work_records.index')->with('success', 'ออเดอร์ถูกบันทึกแล้ว');
       } catch (\Exception $e) {
           DB::rollBack();
           return redirect()->back()->withInput()->with('error', 'ไม่สามารถสร้างออเดอร์ได้: ' . $e->getMessage());
       }
   }
}
