<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Poir
 * @package Modules\ContractualProcess\Models
 * @version January 14, 2021, 7:09 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheets
 * @property string $name
 * @property boolean $state
 */
class Poir extends Model
{
    use SoftDeletes;

    public $table = 'pc_poir';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'state'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'state' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcInvestmentTechnicalSheets()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet::class, 'pc_poir_id');
    }
}
