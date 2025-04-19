<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Return1 extends Model
{
    use HasFactory;
    protected $table = 'returns'; // ระบุชื่อตารางถ้าไม่ใช่แบบมาตรฐาน (ชื่อของตารางคือ "returns")

    protected $fillable = [
        'sale_id',
        'return_date',
        'reason',
        'user_id',
        'shop_id',
    ];

    // ความสัมพันธ์กับโมเดล Shop
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    // ความสัมพันธ์กับโมเดล User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ความสัมพันธ์กับโมเดล Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
    public function items()
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }



}
