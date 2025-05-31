<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $currencies_id
 * @property int|null $company_id
 * @property string|null $currency_code
 * @property string|null $name
 * @property string $exchange_rate
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $active
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $currency_symbol
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCurrenciesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Currency extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'currency_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'active'
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
