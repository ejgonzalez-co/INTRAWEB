<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NameProject
 * @package Modules\ContractualProcess\Models
 * @version January 13, 2021, 4:01 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheets
 * @property string $name
 * @property boolean $state
 */
class NameProject extends Model
{
    use SoftDeletes;

    public $table = 'pc_name_projects';
    
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
        'state' => 'integer'
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
    public function investmentTechnicalSheets()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\InvestmentTechnicalSheet::class, 'pc_name_projects_id');
    }
}
