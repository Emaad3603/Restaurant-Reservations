<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategoryTranslation extends Model
{
    protected $table = 'menu_categories_translation';
    public $timestamps = false;

    protected $fillable = [
        'menu_categories_id',
        'name',
        'language_code'
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_categories_id', 'menu_categories_id');
    }
} 