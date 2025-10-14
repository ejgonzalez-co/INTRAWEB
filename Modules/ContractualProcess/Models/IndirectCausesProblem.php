<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class IndirectCausesProblem
 * @package Modules\ContractualProcess\Models
 * @version January 15, 2021, 12:35 am -05
 *
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 * @property string $name
 */
class IndirectCausesProblem extends Model
{
    use SoftDeletes;

    public $table = 'pc_indirect_causes_problem';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_investment_technical_sheets_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function investmentTechnicalSheets()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\InvestmentTechnicalSheets::class, 'pc_investment_technical_sheets_id');
    }
}
