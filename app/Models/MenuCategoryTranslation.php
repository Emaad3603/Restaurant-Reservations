<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $menu_categories_translation_id
 * @property int|null $menu_categories_id
 * @property string|null $language_code
 * @property string|null $name
 * @property-read \App\Models\MenuCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategoryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategoryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategoryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategoryTranslation whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategoryTranslation whereMenuCategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategoryTranslation whereMenuCategoriesTranslationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategoryTranslation whereName($value)
 * @mixin \Eloquent
 */
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