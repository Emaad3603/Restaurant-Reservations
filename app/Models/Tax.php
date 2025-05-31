<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $taxes_id
 * @property int|null $company_id
 * @property string|null $label
 * @property int|null $percentage
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property int|null $active
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaxTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereTaxesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereValue($value)
 * @mixin \Eloquent
 */
class Tax extends Model
{
    protected $table = 'taxes';
    protected $primaryKey = 'taxes_id';
    
    protected $fillable = [
        'name',
        'percentage',
        'active'
    ];
    
    public function translations()
    {
        return $this->hasMany(TaxTranslation::class, 'taxes_id', 'taxes_id');
    }
    
    public function getTranslation($languageCode = 'en')
    {
        return $this->translations()->where('language_code', $languageCode)->first();
    }
} 