<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $menu_categories_id
 * @property int|null $company_id
 * @property string $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property string|null $label
 * @property string|null $background_url
 * @property int|null $menus_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuSubcategory> $subcategories
 * @property-read int|null $subcategories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuCategoryTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereBackgroundUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereMenuCategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereMenusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
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

    public function translations()
    {
        return $this->hasMany(MenuCategoryTranslation::class, 'menu_categories_id', 'menu_categories_id');
    }

    public function getTranslation($languageCode = 'en')
    {
        return $this->translations()->where('language_code', $languageCode)->first();
    }
} 