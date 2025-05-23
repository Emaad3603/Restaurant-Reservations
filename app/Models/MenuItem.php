<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'items_id';
    public $timestamps = false;

    protected $fillable = [
        'items_id',
        'price',
        'currencies_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'menus_id'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menus_id', 'menus_id');
    }

    public function translations()
    {
        return $this->hasMany(MenuItemTranslation::class, 'items_id', 'items_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id', 'currencies_id');
    }

    public function translation()
    {
        return $this->hasOne(MenuItemTranslation::class, 'items_id', 'items_id');
    }

    public function menusItems()
    {
        return $this->hasMany(MenuItem::class, 'items_id', 'items_id');
    }
} 