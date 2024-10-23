<?php

namespace App\Http\Controllers;
use App\Models\Return1;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

use App\Models\Product;
use App\Models\ReturnItem;

use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(Request $request)
    {    $user = Auth::user();
                $search = $request->input('search');

        $returns = Return1::with('shop') // ตรวจสอบว่า `shop` มีอยู่ในความสัมพันธ์
            ->when($search, function ($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                             ->orWhereHas('shop', function($q) use ($search) {
                                 $q->where('name', 'LIKE', "%{$search}%");
                             });
            })
            ->paginate(10); // หรือเปลี่ยนจำนวนแสดงผลตามต้องการ

        return view('returns.index', compact('returns','user'));
    }
    public function create()
    {
        $users = User::all();

        $user = Auth::user();
        $shops = Shop::all();
        $products = Product::all();

        // ส่งข้อมูลไปยังมุมมองในรูปแบบของอาร์เรย์
        return view('returns.create', [
            'user' => $user,
            'shops' => $shops,
            'users' => $users,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|integer',
            'return_date' => 'required|date',
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,product_id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
            'reason' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,user_id',
            'shop_id' => 'required|integer|exists:shops,shop_id',
        ]);

        // Create the return record
        $return = Return1::create([
            'sale_id' => $request->input('sale_id'),
            'return_date' => $request->input('return_date'),
            'reason' => $request->input('reason'),
            'user_id' => $request->input('user_id'),
            'shop_id' => $request->input('shop_id'),
        ]);

        // Create return items
        foreach ($request->input('product_ids') as $index => $product_id) {
            ReturnItem::create([
                'return_id' => $return->id,
                'product_id' => $product_id,
                'quantity' => $request->input('quantities')[$index],
            ]);
        }

        return redirect()->back()->with('success', 'สร้างข้อมูลการคืนสินค้าสำเร็จ');
    }

    public function show(Return1 $return)
    {    $user = Auth::user();
        $return->load('items.product'); // Load the related products

        return view('returns.show', compact('return','user'));
    }

    public function edit(Return1 $return)
    {        $user = Auth::user();

        $products = Product::all();
        $shops = Shop::all();
        $users = User::all();
        return view('returns.edit', compact('return', 'products', 'shops', 'users','user'));    }

        public function update(Request $request, Return1 $return)
        {
            // Validate the incoming request data
            $request->validate([
                'sale_id' => 'required|integer',
                'return_date' => 'required|date',
                'product_ids' => 'required|array',
                'quantities' => 'required|array',
                'reason' => 'required|string|max:255',
                'user_id' => 'required|integer',
                'shop_id' => 'required|integer',
            ]);

            // Update the Return1 record
            $return->update([
                'sale_id' => $request->input('sale_id'),
                'return_date' => $request->input('return_date'),
                'reason' => $request->input('reason'),
                'user_id' => $request->input('user_id'),
                'shop_id' => $request->input('shop_id'),
            ]);

            // Update ReturnItem records
            $existingProductIds = $return->items->pluck('product_id')->toArray();
            $newProductIds = $request->input('product_ids');
            $newQuantities = $request->input('quantities');

            // Remove items that are no longer in the list
            foreach ($return->items as $item) {
                if (!in_array($item->product_id, $newProductIds)) {
                    $item->delete();
                }
            }

            // Add or update items
            foreach ($newProductIds as $index => $product_id) {
                // If the product is not in the existing items, create a new record
                if (!in_array($product_id, $existingProductIds)) {
                    ReturnItem::create([
                        'return_id' => $return->id,
                        'product_id' => $product_id,
                        'quantity' => $newQuantities[$index],
                    ]);
                } else {
                    // Update existing items
                    $item = ReturnItem::where('return_id', $return->id)
                        ->where('product_id', $product_id)
                        ->first();
                    if ($item) {
                        $item->update([
                            'quantity' => $newQuantities[$index],
                        ]);
                    }
                }
            }

            return redirect()->route('returns.index')->with('success', 'ข้อมูลการคืนสินค้าถูกอัปเดตเรียบร้อยแล้ว');
        }

    public function destroy(Return1 $return)
    {
        $return->delete();
        return redirect()->route('returns.index')->with('success', 'ลบข้อมูลการคืนสินค้าสำเร็จ');
    }
}
