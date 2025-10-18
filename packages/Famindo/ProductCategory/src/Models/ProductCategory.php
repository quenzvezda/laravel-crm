<?php

namespace Famindo\ProductCategory\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];
}

