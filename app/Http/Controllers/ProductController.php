<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Product; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

use App\Models\Sale;

class ProductController extends Controller
{
 // app/Http/Controllers/ProductController.php
 public function destroy($id)
 {
     // ดึงข้อมูลสินค้าที่ต้องการลบโดยใช้ ID
     $product = Product::findOrFail($id);

     // ทำการลบสินค้า
     $product->delete();

     // กลับไปยังหน้ารายการสินค้าพร้อมกับข้อความแสดงความสำเร็จ
     return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
 }

public function create()
{
    $user = Auth::User();
    $categories = Category::all(); // ดึงข้อมูลหมวดหมู่ทั้งหมด

    $warehouses = Warehouse::all();

    return view('Product.create', compact('user','warehouses','categories'));

}
public function edit($id)
{
    $user = Auth::user();

    // ดึงข้อมูลสินค้าที่ต้องการแก้ไข
    $product = Product::findOrFail($id);
    $categories = Category::all(); // ดึงข้อมูลหมวดหมู่ทั้งหมด

    // ดึงข้อมูลคลังสินค้าทั้งหมด
    $warehouses = Warehouse::all();

    // ส่งข้อมูลไปยังมุมมอง 'Product.edit'
    return view('Product.edit', compact('user', 'product', 'warehouses','categories'));
}

public function store(Request $request)
{

    $request->validate([
        'product_id1' => 'required|max:20',
        // เพิ่มกฎสำหรับฟิลด์อื่น ๆ ที่ต้องการ
    ]);
    // Validate and store the product
    $validatedData = $request->validate([
        'name' => 'required|max:20',
        'description' => 'required|max:255',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'expiration_date' => 'required|date',
        'Warehouse' => 'required|integer',
        'product_id1' => 'nullable|string|max:255',
        'category_id' => 'nullable', // ตรวจสอบว่า category_id มีอยู่ในตาราง categorie
    ]);

    Product::create($validatedData);


    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}
public function update(Request $request, $id)
{
    // ตรวจสอบความถูกต้องของข้อมูล
    $validatedData = $request->validate([
        'name' => 'required|max:20',
        'description' => 'required|max:255',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'expiration_date' => 'required|date',
        'Warehouse' => 'required|integer',
        'product_id1' => 'nullable|string|max:255',
        'category_id' => 'nullable', // ตรวจสอบว่า category_id มีอยู่ในตาราง categorie
        // เพิ่มการ validate สำหรับ product_id1
    ]);

    // ดึงข้อมูลสินค้าที่ต้องการอัปเดต
    $product = Product::findOrFail($id);

    // อัปเดตข้อมูลสินค้า
    $product->update($validatedData);

    // กลับไปยังหน้ารายการสินค้าพร้อมกับข้อความแสดงความสำเร็จ
    return redirect()->route('products.index')->with('success', 'Product updated successfully.');
}


public function show(Product $product)
{$user = Auth::User();
    return view('Product.show', compact('product','user'));
}
public function index()
{
    $user = Auth::user();

    // ดึงข้อมูลสินค้าทั้งหมด
    $products = Product::all();
    $categories = Category::all(); // ดึงข้อมูลหมวดหมู่ทั้งหมด

    // ดึงข้อมูลยอดขาย
    $sales = Sale::select('product_id', DB::raw('SUM(quantity) as total_sold'))
                 ->groupBy('product_id')
                 ->get();

    // ดึงข้อมูลที่อยู่ในคลังสินค้า
    $productMovements = Warehouse::with('warehouse') // เชื่อมโยงกับโมเดล Warehouse
        ->get();

    return view('Product.index', compact('products', 'user', 'sales', 'productMovements','categories'));
}

}
