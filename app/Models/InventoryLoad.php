<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLoad extends Model
{
    use HasFactory;

    // ระบุชื่อของตารางที่ใช้ในฐานข้อมูล (ถ้าไม่ระบุ Laravel จะใช้ชื่อ 'inventory_loads' ตามค่าเริ่มต้น)
    protected $table = 'inventory_loads';

    // ระบุฟิลด์ที่สามารถแก้ไขได้ผ่าน Mass Assignment
    protected $fillable = [
        'id',
        'work_record_id',
        'product_id',// รหัสสินค้าใน work record ที่โหลดกลับ
        'user_id',                // รหัสผู้ใช้ที่ทำการโหลด
        'quantity',
        'warehouse_id',
        'created_by'              // จำนวนสินค้าที่โหลดกลับ
    ];

    // ความสัมพันธ์กับโมเดล WorkRecordProduct
    public function workRecordProduct()
    {
        return $this->belongsTo(WorkRecordProduct::class);
    }

    // ความสัมพันธ์กับโมเดล User (ผู้ใช้)

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function workRecord()
    {
        return $this->belongsTo(WorkRecordProduct::class, 'work_record_id');
    }
}
