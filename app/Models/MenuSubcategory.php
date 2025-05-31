<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $menu_subcategories_id
 * @property int|null $menu_categories_id
 * @property int|null $company_id
 * @property string $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property string|null $label
 * @property string|null $background_url
 * @property-read \App\Models\MenuCategory|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereBackgroundUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereMenuCategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereMenuSubcategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSubcategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
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