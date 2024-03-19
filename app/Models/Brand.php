<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'brand', 'is_active', 'img', 'desc'
    ];

    public function sub_categories()
    {
        return $this->hasMany('App\Models\SubCategory')->orderBy('sub_category', 'ASC');
    }
    public function product_prepaids()
    {
        return $this->hasMany('App\Models\ProductPrepaid');
    }

    public function product_pasca()
    {
        return $this->hasMany('App\Models\ProductPasca');
    }
}
