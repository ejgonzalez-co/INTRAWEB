<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class EnvironmentalImpact
 * @package Modules\ContractualProcess\Models
 * @version January 26, 2021, 11:22 am -05
 *
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 * @property integer $environmental_component
 * @property string $other_environmental_component
 * @property string $impact_description
 * @property string $requires_environmental_license
 * @property string $license_number
 * @property string|\Carbon\Carbon $expedition_date
 */
class EnvironmentalImpact extends Model
{
    use SoftDeletes;

    public $table = 'pc_environmental_impacts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'environmental_component',
        'other_environmental_component',
        'impact_description',
        'requires_environmental_license',
        'license_number',
        'expedition_date'
    ];

    protected $appends = [
        'environmental_component_name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer',
        'environmental_component' => 'integer',
        'other_environmental_component' => 'string',
        'impact_description' => 'string',
        'requires_environmental_license' => 'string',
        'license_number' => 'string',
        'expedition_date' => 'datetime'
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
     * Obtiene el nombre del componente ambiental
     * @return 
     */
    public function getEnvironmentalComponentNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.environmental_component'), 'id', $this->environmental_component)->name;   
    }
}
