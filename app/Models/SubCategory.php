<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'category_id', 'brand_id', 'sub_category', 'is_active', 'desc'
    ];

    public function products()
    {
        return $this->hasMany('App\Models\Product')->orderBy('id', 'ASC');
    }
}
