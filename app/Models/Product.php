<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'category_id', 'price', 'cost_price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function stockIn($branchId)
    {
        return $this->stocks()->where('branch_id', $branchId)->first()?->quantity ?? 0;
    }
}
