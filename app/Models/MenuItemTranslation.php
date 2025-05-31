<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $items_translation_id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $language_code
 * @property int|null $items_id
 * @property-read \App\Models\MenuItem|null $menuItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItemTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItemTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItemTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItemTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItemTranslation whereItemsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItemTranslation whereItemsTranslationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItemTranslation whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItemTranslation whereName($value)
 * @mixin \Eloquent
 */
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