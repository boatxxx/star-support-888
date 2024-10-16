<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkRecordItem; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Shop; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\User; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

class WorkRecord extends Model
{
    use HasFactory;
    protected $table = 'work_records';
    protected $primaryKey = 'id'; // เปลี่ยน `shop_id` เป็นคอลัมน์ primary key ของคุณ

    // กำหนดชื่อของคอลัมน์ที่สามารถกรอกข้อมูลได้ (fillable)

    protected $fillable = [
        'product_id',
        'quantity',
        'order_date',
        'description',
        'shop_id',
        'user_id',
        'status',
    ];
    public function items()
    {
        return $this->hasMany(WorkRecordItem::class);
    }

    // Define the relationship to Shop
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'work_record_product', 'work_record_id', 'product_id')
                    ->withPivot('quantity') // ถ้ามีการเก็บจำนวนสินค้าด้วย
                    ->withTimestamps(); // ถ้าต้องการเก็บข้อมูล timestamp ในตาราง pivot
    }

    // Define the relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // กำหนดชื่อของคอลัมน์ที่ไม่ต้องการให้กรอกข้อมูล (guarded)
    protected $guarded = [];

    // การกำหนดชนิดข้อมูลสำหรับคอลัมน์ที่เป็นวันที่
    protected $dates = [
        'order_date',
    ];
}
