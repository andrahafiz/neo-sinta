<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "products";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name_product', 'slug', 'categories_id', 'price', 'description', 'stock', 'image'
    ];

    protected $with = ['categories'];
    protected $cast = [
        'price' => 'integer'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }
}
