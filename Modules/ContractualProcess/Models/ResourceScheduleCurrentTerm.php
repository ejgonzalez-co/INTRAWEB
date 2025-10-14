<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class ResourceScheduleCurrentTerm
 * @package Modules\ContractualProcess\Models
 * @version January 26, 2021, 8:47 am -05
 *
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 * @property string $description
 * @property integer $week_id
 * @property boolean $week
 */
class ResourceScheduleCurrentTerm extends Model
{
    // use SoftDeletes;

    public $table = 'pc_resource_schedule_current_term';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'description',
        'month',
        'week_id',
        'week'
    ];

    protected $appends = [
        // 'month_name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_investment_technical_sheets_id' => 'integer',
        'description' => 'string',
        'week_id' => 'integer',
        'week' => 'array'
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

    // /**
    //  * Obtiene el nombre del mes
    //  * @return 
    //  */
    // public function getMonthNameAttribute() {
    //     return AppBaseController::getObjectOfList(config('contractual_process.schedule_month'), 'id', $this->month)->name;   
    // }
}
