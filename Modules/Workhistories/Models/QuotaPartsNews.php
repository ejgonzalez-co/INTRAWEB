<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QuotaPartsNews
 * @package Modules\Workhistories\Models
 * @version December 10, 2020, 6:56 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesCp $workHistoriesCp
 * @property Modules\Workhistories\Models\WorkHistoriesCpPensionado $cpPensionados
 * @property Modules\Workhistories\Models\User $users
 * @property string $new
 * @property string $type_document
 * @property string $users_name
 * @property integer $users_id
 * @property integer $cp_pensionados_id
 * @property integer $work_histories_cp_id
 */
class QuotaPartsNews extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_cp_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'new',
        'type_document',
        'users_name',
        'users_id',
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
        'new' => 'string',
        'type_document' => 'string',
        'users_name' => 'string',
        'users_id' => 'integer',
        'cp_pensionados_id' => 'integer',
        'work_histories_cp_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\User::class, 'users_id');
    }
}
