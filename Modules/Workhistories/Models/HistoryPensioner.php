<?php

namespace Modules\Workhistories\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryPensioner
 * @package Modules\Workhistories\Models
 * @version December 6, 2020, 8:01 pm -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property Modules\Workhistories\Models\WorkHistoriesP $workHistoriesP
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
 * @property integer $work_histories_p_id
 * @property integer $users_id
 */
class HistoryPensioner extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_history';

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
        'phone2',
        'email',
        'gender',
        'group_ethnic',
        'birth_date',
        'notification',
        'level_study',
        'phone_event',
        'name_event',
        'state',
        'total_documents',
        'users_id',
        'users_name',
        'level_study_other',
        'rh',
        'date_document',
        'date_admission',
        'type_laboral',
        'level_laboral',
        'denomination_laboral',
        'numer_account',
        'area',
        'salary',
        'health',
        'pension',
        'layoffs',
        'embargo',
        'embargo_owner',
        'embargo_value',
        'address_event',
        'relationship_event',
        'dependencias_name',
        'grade_laboral',
        'state_civil',
        'arl',
        'observation_deceased',
        'deceased',
        'work_histories_p_id',
        'users_id'
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
        'group_ethnic' => 'string',
        'birth_date' => 'date',
        'notification' => 'string',
        'level_study' => 'string',
        'phone_event' => 'string',
        'name_event' => 'string',
        'state' => 'boolean',
        'users_name' => 'string',
        'work_histories_p_id' => 'integer',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type_document' => 'nullable|string|max:45',
        'number_document' => 'nullable|string|max:45',
        'name' => 'nullable|string|max:45',
        'surname' => 'nullable|string|max:45',
        'address' => 'nullable|string|max:250',
        'phone' => 'nullable|string|max:45',
        'email' => 'nullable|string|max:45',
        'group_ethnic' => 'nullable|string|max:45',
        'birth_date' => 'nullable',
        'notification' => 'nullable|string|max:45',
        'level_study' => 'nullable|string|max:45',
        'phone_event' => 'nullable|string|max:45',
        'name_event' => 'nullable|string|max:125',
        'state' => 'nullable|boolean',
        'users_name' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'work_histories_p_id' => 'required',
        'users_id' => 'required'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesP()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesP::class, 'work_histories_p_id');
    }
}
