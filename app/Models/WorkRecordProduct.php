<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkRecordProduct extends Model
{
    use HasFactory;

    // กำหนดชื่อ table

    protected $table = 'work_record_product';

    protected $fillable = [
        'work_record_id',
        'product_id',
        'quantity',
        'created_by',
        'is_hidden'
    ];

    // ความสัมพันธ์กับ WorkRecord
    public function workRecord()
    {
        return $this->belongsTo(WorkRecord::class);
    }

    // ความสัมพันธ์กับ Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ความสัมพันธ์กับ User
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
