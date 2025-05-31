<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $discounts_id
 * @property int|null $company_id
 * @property string|null $label
 * @property int|null $percentage
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property int|null $active
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DiscountTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereDiscountsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereValue($value)
 * @mixin \Eloquent
 */
class Discount extends Model
{
    protected $table = 'discounts';
    protected $primaryKey = 'discounts_id';
    
    protected $fillable = [
        'name',
        'percentage',
        'active'
    ];
    
    public function translations()
    {
        return $this->hasMany(DiscountTranslation::class, 'discounts_id', 'discounts_id');
    }
    
    public function getTranslation($languageCode = 'en')
    {
        return $this->translations()->where('language_code', $languageCode)->first();
    }
} 