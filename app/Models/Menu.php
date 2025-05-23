<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'menus_id';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'restaurants_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'label'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurants_id', 'restaurants_id');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'menus_id', 'menus_id');
    }
} 