<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ManagementUnit
 * @package Modules\ContractualProcess\Models
 * @version January 13, 2021, 4:13 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheets
 * @property string $name
 * @property boolean $state
 */
class ManagementUnit extends Model
{
    use SoftDeletes;

    public $table = 'pc_management_unit';
    
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
        return $this->hasMany(\Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet::class, 'pc_management_unit_id');
    }
}
