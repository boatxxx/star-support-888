<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionProduct extends Model
{
    use HasFactory;

    protected $table = 'promotion_products';

    protected $primaryKey = 'promotion_product_id';

    protected $fillable = [
        'promotion_id',
        'product_id',
        'applied_discount',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'promotion_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    } }
