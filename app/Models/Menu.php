<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $menus_id
 * @property int|null $company_id
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property string|null $label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $menuItems
 * @property-read int|null $menu_items_count
 * @property-read \App\Models\Restaurant|null $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereMenusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereUpdatedBy($value)
 * @mixin \Eloquent
 */
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