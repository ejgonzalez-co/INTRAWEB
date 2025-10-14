<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class ScheduleResourcesPreviousPeriod
 * @package Modules\ContractualProcess\Models
 * @version January 26, 2021, 8:48 am -05
 *
 * @property Modules\ContractualProcess\Models\PcInvestmentTechnicalSheet $pcInvestmentTechnicalSheets
 * @property integer $pc_investment_technical_sheets_id
 * @property string $description
 * @property boolean $week
 */
class ScheduleResourcesPreviousPeriod extends Model
{
    // use SoftDeletes;

    public $table = 'pc_schedule_resources_previous_periods';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'pc_investment_technical_sheets_id',
        'description',
        'week'
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
}
