<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $items_id
 * @property int|null $company_id
 * @property int|null $menu_categories_id
 * @property int|null $menu_subcategories_id
 * @property int|null $items_transelation_id
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property string|null $label
 * @property-read \App\Models\Currency|null $currency
 * @property-read \App\Models\Menu|null $menu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MenuItem> $menusItems
 * @property-read int|null $menus_items_count
 * @property-read \App\Models\MenuItemTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItemTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereItemsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereItemsTranselationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereMenuCategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereMenuSubcategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUpdatedBy($value)
 * @mixin \Eloquent
 */
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