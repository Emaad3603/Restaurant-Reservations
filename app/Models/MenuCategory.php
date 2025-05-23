<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    protected $table = 'menu_categories';
    protected $primaryKey = 'menu_categories_id';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'created_by',
        'label',
        'background_url',
    ];

    public function subcategories()
    {
        return $this->hasMany(MenuSubcategory::class, 'menu_categories_id');
    }
} 