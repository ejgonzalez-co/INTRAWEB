<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PreviousStudiesInvestmentSheets
 * @package Modules\ContractualProcess\Models
 * @version July 1, 2021, 10:53 am -05
 *
 * @property Modules\ContractualProcess\Models\PcPreviousStudy $pcPreviousStudies
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 */
class PreviousStudiesInvestmentSheets extends Model
{

    public $table = 'pc_previous_studies_has_pc_investment_technical_sheets';
    
    public $timestamps = false;



    
    
    public $fillable = [
        'pc_investment_technical_sheets_id','pc_previous_studies_id' 
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'pc_previous_studies_id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcPreviousStudies() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPreviousStudies::class, 'pc_previous_studies_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcInvestmentTechnicalSheets() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\InvestmentTechnicalSheet::class, 'pc_investment_technical_sheets_id')->with(['nameProjects']);
    }
}
