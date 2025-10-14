<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PcPreviousStudiesRadication
 * @package Modules\ContractualProcess\Models
 * @version January 24, 2021, 9:30 pm -05
 *
 * @property Modules\ContractualProcess\Models\PcPreviousStudy $pcPreviousStudies
 * @property string $process
 * @property string $object
 * @property string $boss
 * @property string $value
 * @property string|\Carbon\Carbon $date_send
 * @property string $notification
 * @property integer $pc_previous_studies_id
 */
class PcPreviousStudiesRadication extends Model
{
    use SoftDeletes;

    public $table = 'pc_previous_studies_radication';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'process',
        'object',
        'boss',
        'value',
        'date_send',
        'notification',
        'pc_previous_studies_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'process' => 'string',
        'object' => 'string',
        'boss' => 'string',
        'value' => 'string',
        'date_send' => 'datetime',
        'notification' => 'string',
        'pc_previous_studies_id' => 'integer'
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
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPreviousStudies::class, 'pc_previous_studies_id');
    }
}
