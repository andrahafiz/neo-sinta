<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $primaryKey = 'id';
    protected $fillable = ['category', 'slug'];
    public $timestamps = false;
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'categories_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            $relationMethods = ['products'];

            foreach ($relationMethods as $relationMethod) {
                if ($product->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }
}
