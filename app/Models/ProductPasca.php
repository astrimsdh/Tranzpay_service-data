<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPasca extends Model
{
    protected $table  = 'product_pasca';

    protected $primariKey = 'id';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'product_name',
        'product_category',
        'product_provider',
        'product_seller',
        'product_transaction_admin',
        'product_transaction_fee',
        'product_sku',
    ];

    public function scopeFindProductBySKU($query, $value)
    {
        $query->where('product_sku', $value);
    }
}
