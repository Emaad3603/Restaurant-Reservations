<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $discounts_translation_id
 * @property int|null $discounts_id
 * @property string|null $language_code
 * @property string|null $name
 * @property string|null $description
 * @property-read \App\Models\Discount|null $discount
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountTranslation whereDiscountsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountTranslation whereDiscountsTranslationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountTranslation whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountTranslation whereName($value)
 * @mixin \Eloquent
 */
class DiscountTranslation extends Model
{
    protected $table = 'discounts_translation';
    
    protected $fillable = [
        'discounts_id',
        'name',
        'language_code'
    ];
    
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discounts_id', 'discounts_id');
    }
} 