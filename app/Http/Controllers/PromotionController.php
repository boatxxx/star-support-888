<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Promotion; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
 // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\PromotionCondition; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\PromotionDiscount; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\PromotionProduct; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

class PromotionController extends Controller
{
    public function create()
    {        $products = Product::all();

        $user = Auth::User();

        return view('promotions.create', compact('user','products'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:percentage_discount,product_specific_discount,conditional_discount',
            'status' => 'required|in:active,inactive',
            'conditions.*.condition_type' => 'nullable|in:buy_x_get_y,quantity_based',
            'conditions.*.condition_value' => 'nullable|string',
            'discounts.*.discount_type' => 'nullable|in:percentage,fixed_amount',
            'discounts.*.discount_value' => 'nullable|numeric',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.applied_discount' => 'nullable|numeric',

        ]);

        // Create the promotion
        $promotion = Promotion::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
        ]);

        // Store conditions if any
        if ($request->has('conditions')) {
            foreach ($request->input('conditions') as $condition) {
                if (!empty($condition['condition_type'])) {
                    PromotionCondition::create([
                        'promotion_id' => $promotion->promotion_id,
                        'condition_type' => $condition['condition_type'],
                        'condition_value' => $condition['condition_value'],
                    ]);
                }
            }
        }

        // Store discounts if any
        if ($request->has('discounts')) {
            foreach ($request->input('discounts') as $discount) {
                if (!empty($discount['discount_type'])) {
                    PromotionDiscount::create([
                        'promotion_id' => $promotion->promotion_id,
                        'discount_type' => $discount['discount_type'],
                        'discount_value' => $discount['discount_value'],
                    ]);
                }
            }
        }

        // Store products if any
        if ($request->has('products')) {
            foreach ($request->input('products') as $product) {
                if (isset($product['product_id']) && !is_null($product['product_id'])) {
                    PromotionProduct::create([
                        'promotion_id' => $promotion->promotion_id,
                        'product_id' => $product['product_id'],
                        'applied_discount' => $product['applied_discount'] ?? null,
                    ]);
                } else {
                    \Log::error('Product ID is null or not set', ['product' => $product]);
                    // Optionally, handle this situation appropriately
                }
            }
        }

        // Redirect with success message
        return redirect()->route('promotions.index')->with('success', 'Promotion created successfully!');
    }



    public function index()
    {
        $user = Auth::User();
        $products = Product::all();

        $promotions = Promotion::all();
        return view('promotions.index', compact('promotions','user','products'));
    }
    public function edit(Promotion $promotion)
    {
        // ดึงข้อมูลที่ตรงกัน
        $conditions = PromotionCondition::where('promotion_id', $promotion->promotion_id)->get();
        $discounts = PromotionDiscount::where('promotion_id', $promotion->promotion_id)->get();
        $products = PromotionProduct::where('promotion_id', $promotion->promotion_id)->get();
        $products1 = Product::all();
        // ดึงข้อมูลของผู้ใช้ปัจจุบัน
        $user = Auth::user();

        // ส่งข้อมูลไปยัง view
        return view('promotions.edit', compact('promotion', 'conditions', 'discounts', 'products', 'user','products1'));
    }
    public function update(Request $request, Promotion $promotion)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:percentage_discount,product_specific_discount,conditional_discount',
            'status' => 'required|in:active,inactive',
            'conditions.*.condition_type' => 'nullable|in:buy_x_get_y,quantity_based',
            'conditions.*.condition_value' => 'nullable|string',
            'discounts.*.discount_type' => 'nullable|in:percentage,fixed_amount',
            'discounts.*.discount_value' => 'nullable|numeric',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.applied_discount' => 'nullable|numeric',
        ]);

        // Update the promotion
        $promotion->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
        ]);

        // Update conditions
        $promotion->conditions()->delete(); // Delete existing conditions
        if ($request->has('conditions')) {
            foreach ($request->input('conditions') as $condition) {
                if (!empty($condition['condition_type'])) {
                    PromotionCondition::create([
                        'promotion_id' => $promotion->promotion_id,
                        'condition_type' => $condition['condition_type'],
                        'condition_value' => $condition['condition_value'],
                    ]);
                }
            }
        }

        // Update discounts
        $promotion->discounts()->delete(); // Delete existing discounts
        if ($request->has('discounts')) {
            foreach ($request->input('discounts') as $discount) {
                if (!empty($discount['discount_type'])) {
                    PromotionDiscount::create([
                        'promotion_id' => $promotion->promotion_id,
                        'discount_type' => $discount['discount_type'],
                        'discount_value' => $discount['discount_value'],
                    ]);
                }
            }
        }

        // Update products
        $promotion->products()->delete(); // Delete existing promotion products
        if ($request->has('products')) {
            foreach ($request->input('products') as $product) {
                if (isset($product['product_id']) && !is_null($product['product_id'])) {
                    PromotionProduct::create([
                        'promotion_id' => $promotion->promotion_id,
                        'product_id' => $product['product_id'],
                        'applied_discount' => $product['applied_discount'] ?? null,
                    ]);
                } else {
                    \Log::error('Product ID is null or not set', ['product' => $product]);
                    // Optionally, handle this situation appropriately
                }
            }
        }

        // Redirect with success message
        return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully!');
    }
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully.');
    }
}
