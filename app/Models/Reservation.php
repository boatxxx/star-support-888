<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'product_id',
        'user_id',
        'shop_id',          // เพิ่ม shop_id ที่นี่
        'quantity',
        'reservation_date',
        'status',           // เพิ่ม status ที่นี่
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id'); // แก้ไขถ้าชื่อคอลัมน์แตกต่างกัน
    }

    // ความสัมพันธ์กับร้านค้า
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    // ความสัมพันธ์กับผู้ใช้
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
