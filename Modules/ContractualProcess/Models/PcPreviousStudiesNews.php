<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class PcPreviousStudiesNews
 * @package Modules\ContractualProcess\Models
 * @version January 15, 2021, 4:17 pm -05
 *
 * @property Modules\ContractualProcess\Models\PcPreviousStudy $pcPreviousStudies
 * @property string $user_name
 * @property string $state
 * @property string $observation
 * @property integer $pc_previous_studies_id
 */
class PcPreviousStudiesNews extends Model
{
    use SoftDeletes;

    public $table = 'pc_previous_studies_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_name',
        'state',
        'observation',
        'pc_previous_studies_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_name' => 'string',
        'state' => 'integer',
        'observation' => 'string',
        'pc_previous_studies_id' => 'integer'
    ];

    protected $appends = [
        'state_name',
        'state_colour',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_previous_studies_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcPreviousStudies()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPreviousStudy::class, 'pc_previous_studies_id');
    }

        /**
     * Obtiene el nombre del estado
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('contractual_process.pc_studies_previous'), 'id', $this->state)->name;        
    }

    /**
     * Obtiene el color del estado
     * @return 
     */
    public function getStateColourAttribute() {       
        return AppBaseController::getObjectOfList(config('contractual_process.pc_studies_previous'), 'id', $this->state)->colour;
        
    }
}
