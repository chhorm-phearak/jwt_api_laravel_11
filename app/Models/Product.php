<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'images',
        'description',
        'price',
        'category_id',
    ];
    /**
     * Function: orders
     * Relation: hasMany = table Parrents
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id', 'id');
    }

    /**
     * Function: category
     * Relation: belongsTo = table Childs
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
