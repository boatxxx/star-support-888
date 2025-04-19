<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // กำหนดชื่อของตาราง
    protected $table = 'sales';

    // กำหนดฟิลด์ที่สามารถทำการกรอกข้อมูลได้
    protected $fillable = [
        'shop_id',
        'product_id',
        'total_price',
        'sale_date',
        'user_id',
        'quantity',
        'paymentCheck'
    ];

    // สร้างความสัมพันธ์กับโมเดล Shop
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ความสัมพันธ์กับตาราง Shop
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    // ความสัมพันธ์กับตาราง User (พนักงานขาย)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function paymentChecks()
    {
        return $this->hasMany(PaymentCheck::class, 'sale_id');
    }
}

