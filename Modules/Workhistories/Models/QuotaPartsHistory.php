<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QuotaPartsHistory
 * @package Modules\Workhistories\Models
 * @version December 10, 2020, 6:55 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesCp $workHistoriesCp
 * @property Modules\Workhistories\Models\WorkHistoriesCpPensionado $cpPensionados
 * @property string $name_company
 * @property integer $time_work
 * @property string $observation
 * @property string $url_document
 * @property integer $cp_pensionados_id
 * @property integer $work_histories_cp_id
 */
class QuotaPartsHistory extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_cp_h';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name_company',
        'time_work',
        'observation',
        'url_document',
        'cp_pensionados_id',
        'work_histories_cp_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_company' => 'string',
        'time_work' => 'integer',
        'observation' => 'string',
        'url_document' => 'string',
        'cp_pensionados_id' => 'integer',
        'work_histories_cp_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cp_pensionados_id' => 'required',
        'work_histories_cp_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesCp()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesCp::class, 'work_histories_cp_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cpPensionados()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesCpPensionado::class, 'cp_pensionados_id');
    }
}
