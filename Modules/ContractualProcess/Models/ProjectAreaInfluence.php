<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ContractualProcess\Models\Cities;
use App\Http\Controllers\AppBaseController;



/**
 * Class ProjectAreaInfluence
 * @package Modules\ContractualProcess\Models
 * @version January 15, 2021, 9:34 am -05
 *
 * @property Modules\ContractualProcess\Models\City $cities
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 * @property integer $cities_id
 * @property integer $service_coverage
 * @property string $neighborhood
 * @property string $commune
 * @property integer $number_inhabitants
 */
class ProjectAreaInfluence extends Model
{
    use SoftDeletes;

    public $table = 'pc_project_area_influence';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'cities_id',
        'service_coverage',
        'neighborhood',
        'commune',
        'number_inhabitants'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer',
        'cities_id' => 'integer',
        'service_coverage' => 'integer',
        'neighborhood' => 'string',
        'commune' => 'string',
        'number_inhabitants' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_investment_technical_sheets_id' => 'required',
        'cities_id' => 'required'
    ];
    
    protected $appends = [
        'municipal_name',
        'service_name'
    ];

        /**
     * Obtiene el nombre del Plan de Desarrollo Municipal
     * @return 
     */
    public function getMunicipalNameAttribute() {
        $municipio = Cities::where('id',$this->cities_id)->get()->first();

        return $municipio['name'];
    }

        /**
     * Obtiene el nombre del Plan de Desarrollo Municipal
     * @return 
     */
    public function getServiceNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.investment_technical_service_coverage'), 'id', $this->service_coverage)->name;   
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cities()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\City::class, 'cities_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcInvestmentTechnicalSheets()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet::class, 'pc_investment_technical_sheets_id');
    }
}
