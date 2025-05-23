<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemTranslation extends Model
{
    protected $table = 'items_translation';
    protected $primaryKey = 'items_translation_id';
    public $timestamps = false;

    protected $fillable = [
        'items_id',
        'language_code',
        'name',
        'description'
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'items_id', 'items_id');
    }
} 