<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $taxes_translation_id
 * @property int|null $taxes_id
 * @property string|null $language_code
 * @property string|null $name
 * @property string|null $description
 * @property-read \App\Models\Tax|null $tax
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxTranslation whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxTranslation whereTaxesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxTranslation whereTaxesTranslationId($value)
 * @mixin \Eloquent
 */
class TaxTranslation extends Model
{
    protected $table = 'taxes_translation';
    
    protected $fillable = [
        'taxes_id',
        'name',
        'language_code'
    ];
    
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'taxes_id', 'taxes_id');
    }
} 