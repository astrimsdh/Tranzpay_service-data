<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrepaid extends Model
{
    protected $table  = 'product_prepaids';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'product_name',
        'product_desc',
        'product_category',
        'category_id',
        'product_provider',
        'product_type',
        'product_seller',
        'product_seller_price',
        'product_buyer_price',
        'product_sku',
        'product_unlimited_stock',
        'product_stock',
        'product_multi',

    ];

    public function scopeFindProductBySKU($query, $value)
    {
        $query->where('product_sku', $value);
    }

    public function scopeFindProductByProvider($query, $provider, $type = null)
    {
        $query->where('product_category', $type)->where('product_provider', $provider);
    }
}
