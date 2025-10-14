<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class SupportingStudyData
 * @package Modules\ContractualProcess\Models
 * @version January 26, 2021, 8:42 am -05
 *
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 * @property string $name
 * @property string|\Carbon\Carbon $study_date
 * @property string $author
 * @property integer $state
 * @property string $storage_place
 * @property integer $support_study_type
 * @property string $product_consultancy
 */
class SupportingStudyData extends Model
{
    use SoftDeletes;

    public $table = 'pc_supporting_study_data';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'name',
        'study_date',
        'author',
        'state',
        'storage_place',
        'support_study_type',
        'product_consultancy'
    ];

    protected $appends = [
        'state_name',
        'support_study_type_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer',
        'name' => 'string',
        'study_date' => 'datetime',
        'author' => 'string',
        'state' => 'integer',
        'storage_place' => 'string',
        'support_study_type' => 'integer',
        'product_consultancy' => 'string'
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
     * Obtiene el nombre del estado
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.state_type_investment_tariff_harmonization'), 'id', $this->state)->name;   
    }

    /**
     * Obtiene el nombre del tipo de estudio
     * @return 
     */
    public function getSupportStudyTypeNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.support_study_type_tariff_harmonization'), 'id', $this->support_study_type)->name;   
    }
}
