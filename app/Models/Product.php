<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{// กำหนด primary key ถ้าจำเป็น
    protected $primaryKey = 'product_id';

    // ระบุ primary key type เป็น 'string' แทน 'bigint'
    protected $keyType = 'string';

    // ใช้ auto-increment
    public $incrementing = true;

    // กำหนดฟิลด์ที่สามารถทำการ mass assignment ได้
    protected $fillable = [
        'Warehouse',
        'name',
        'description',
        'price',
        'stock',
        'expiration_date',
        'product_id1',
        'category_id',
    ];
    public function workRecords()
    {
        return $this->belongsToMany(WorkRecord::class, 'work_record_product', 'product_id', 'work_record_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
    public function sales() {
        return $this->hasMany(Sale::class, 'product_id', 'id'); // เปลี่ยน `Sale` เป็นโมเดลที่คุณใช้
    }
    // กำหนดรูปแบบวันที่ของ attribute
    protected $dates = ['expiration_date'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }



}
