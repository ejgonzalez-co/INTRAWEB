<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class InformationTariffHarmonization
 * @package Modules\ContractualProcess\Models
 * @version January 26, 2021, 8:39 am -05
 *
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 * @property integer $item
 * @property integer $activity
 * @property string $unit
 */
class InformationTariffHarmonization extends Model
{
    use SoftDeletes;

    public $table = 'pc_information_tariff_harmonization';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'item',
        'activity',
        'unit'
    ];

    protected $appends = [
        'activity_name',
        // 'unit_measurement'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer',
        'item' => 'integer',
        'activity' => 'array',
        'unit' => 'string'
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
     * Obtiene el nombre de la activida
     * @return 
     */
    public function getActivityNameAttribute() {
        // Valida si existe actividades asociadas
        if ($this->activity) {
            $activities =  array();
            // Recorre las actividades ingresadas
            foreach ($this->activity as $activity) {
                // Asigna el nombre de la activida
                array_push($activities, $activity['name']);
            }
            // Separa todas las actividades por ,
            return implode(", ", $activities);;
        }
        // return AppBaseController::getObjectOfList(config('contractual_process.activity_item_tariff_harmonization'), 'id', $this->activity)->name;   
    }

    /**
     * Obtiene el nombre de la activida
     * @return 
     */
    // public function getUnitMeasurementAttribute() {
    //     return AppBaseController::getObjectOfList(config('contractual_process.activity_item_tariff_harmonization'), 'id', $this->activity)->unit;   
    // }
}
