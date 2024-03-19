<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'category', 'type', 'is_active', 'img', 'desc', 'category_id'
    ];

    public function sub_categories()
    {
        return $this->hasMany('App\Models\SubCategory')->orderBy('sub_category', 'ASC');
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Customer')->orderBy('customer_name', 'ASC');
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
