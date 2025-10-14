<?php

namespace Modules\Workhistories\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkHistoriesHistory
 * @package Modules\Workhistories\Models
 * @version October 23, 2020, 5:46 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistory $workHistories
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
 * @property integer $work_histories_id
 */
class WorkHistoriesHistory extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_history';

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
        'work_histories_id',
        'users_id',
        'users_name'
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
        'work_histories_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
       // 'work_histories_id' => 'required'
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
    public function workHistories()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistory::class, 'work_histories_id');
    }
}
