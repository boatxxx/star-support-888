<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLoading extends Model
{
    protected $table = 'work_record_product'; // ชื่อของตารางในฐานข้อมูล

    protected $fillable = [
        'work_record_product',
        'work_record_id',
        'product_id',
        'quantity',
        'created_by'
    ];


    // ถ้ามีความสัมพันธ์ (relationships) สามารถกำหนดที่นี่ได้
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // เชื่อมโยงกับ Product โดยใช้ product_id
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by'); // เชื่อมโยงกับ User โดยใช้ created_by
    }
    public function workRecord()
    {
        return $this->belongsTo(WorkRecord::class);
    }}
