<?php

namespace App\Models;
use App\Models\PromotionCondition; // ตรวจสอบการใช้ชื่อโมเดลที่ถูกต้อง

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{    protected $primaryKey = 'promotion_id';

    protected $fillable = [
    'name',
    'description',
    'start_date',
    'end_date',
    'type',
    'status',
];
protected $dates = ['start_date', 'end_date'];
protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
];
public function discounts()
{
    return $this->hasMany(PromotionDiscount::class, 'promotion_id');
}

public function conditions()
{
    return $this->hasMany(PromotionCondition::class, 'promotion_id');
}

public function products()
{
    return $this->hasMany(PromotionProduct::class, 'promotion_id');
}
}
