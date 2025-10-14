<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubstituteHistory
 * @package Modules\Workhistories\Models
 * @version December 7, 2020, 2:35 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesPSubstitute $workHistoriesPSubstitute
 * @property string $type_document
 * @property string $number_document
 * @property string $name
 * @property string $surname
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $depto
 * @property string $city
 * @property string $type_substitute
 * @property string $date_document
 * @property string $birth_date
 * @property string $notification
 * @property string $users_name
 * @property integer $work_histories_p_substitute_id
 * @property integer $users_id
 */
class SubstituteHistory extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_substitute_h';
    
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
        'depto',
        'city',
        'type_substitute',
        'date_document',
        'birth_date',
        'pensioner_id',
        'users_name',
        'state',
        'work_histories_p_substitute_id',
        'users_id',
        'name_pensioner',
        'document_pensioner'


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
        'depto' => 'string',
        'city' => 'string',
        'type_substitute' => 'string',
        'date_document' => 'date',
        'birth_date' => 'date',
        'notification' => 'string',
        'users_name' => 'string',
        'work_histories_p_substitute_id' => 'integer',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'work_histories_p_substitute_id' => 'required',
        'users_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesPSubstitute()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesPSubstitute::class, 'work_histories_p_substitute_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function getWorkHistorie()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistPensioner::class, 'work_histories_p_id');
    }

    
}
