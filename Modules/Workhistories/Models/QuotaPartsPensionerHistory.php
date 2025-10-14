<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QuotaPartsPensionerHistory
 * @package Modules\Workhistories\Models
 * @version December 10, 2020, 3:48 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesCpPensionado $cpPensionados
 * @property Modules\Workhistories\Models\User $users
 * @property string $type_document
 * @property string $number_document
 * @property string $name
 * @property string $surname
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property boolean $gender
 * @property string $group_ethnic
 * @property string $birth_date
 * @property string $notification
 * @property string $level_study
 * @property string $phone_event
 * @property string $name_event
 * @property boolean $state
 * @property string $users_name
 * @property string $time_work
 * @property integer $users_id
 * @property integer $cp_pensionados_id
 */
class QuotaPartsPensionerHistory extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_cp_pensionados_h';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'type_document',
        'number_document',
        'name',
        'surname',
        'address',
        'phone',
        'email',
        'gender',
        'group_ethnic',
        'birth_date',
        'notification',
        'level_study',
        'phone_event',
        'name_event',
        'state',
        'users_name',
        'time_work',
        'users_id',
        'cp_pensionados_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type_document' => 'string',
        'number_document' => 'string',
        'name' => 'string',
        'surname' => 'string',
        'address' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'gender' => 'boolean',
        'group_ethnic' => 'string',
        'birth_date' => 'date',
        'notification' => 'string',
        'level_study' => 'string',
        'phone_event' => 'string',
        'name_event' => 'string',
        'state' => 'boolean',
        'users_name' => 'string',
        'time_work' => 'string',
        'users_id' => 'integer',
        'cp_pensionados_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'cp_pensionados_id' => 'required'
    ];

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
