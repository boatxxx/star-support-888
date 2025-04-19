<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkRecord; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง
use App\Models\Product
; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

class WorkRecordItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'work_record_id',
        'product_id',
        'quantity',
    ];

    public function workRecord()
    {
        return $this->belongsTo(WorkRecord::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
