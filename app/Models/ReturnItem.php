<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'return_id',
        'product_id',
        'quantity'
    ];
    public function return()
    {
        return $this->belongsTo(Return1::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
