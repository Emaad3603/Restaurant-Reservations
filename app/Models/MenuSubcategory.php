<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuSubcategory extends Model
{
    protected $table = 'menu_subcategories';
    protected $primaryKey = 'menu_subcategories_id';
    public $timestamps = false;

    protected $fillable = [
        'menu_categories_id',
        'company_id',
        'created_by',
        'label',
        'background_url',
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_categories_id');
    }

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_subcategories_id');
    }
} 