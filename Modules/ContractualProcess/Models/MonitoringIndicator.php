<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class MonitoringIndicator
 * @package Modules\ContractualProcess\Models
 * @version January 26, 2021, 8:34 am -05
 *
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 * @property integer $indicator_type
 * @property string $description
 * @property string $formula
 */
class MonitoringIndicator extends Model
{
    use SoftDeletes;

    public $table = 'pc_monitoring_indicators';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'indicator_type',
        'description',
        'formula'
    ];

    protected $appends = [
        'indicator_type_name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer',
        'indicator_type' => 'integer',
        'description' => 'string',
        'formula' => 'string'
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
    public function pcInvestmentTechnicalSheets()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet::class, 'pc_investment_technical_sheets_id');
    }

    /**
     * Obtiene el nombre del indicador
     * @return 
     */
    public function getIndicatorTypeNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.indicator_type_monitoring_indicators'), 'id', $this->indicator_type)->name;   
    }
}
