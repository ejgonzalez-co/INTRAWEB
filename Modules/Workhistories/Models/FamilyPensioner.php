<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FamilyPensioner
 * @package Modules\Workhistories\Models
 * @version May 9, 2021, 7:44 pm -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property Modules\Workhistories\Models\WorkHistoriesP $workHistoriesP
 * @property string $name
 * @property string $gender
 * @property string $birth_date
 * @property string $type
 * @property string $state
 * @property string $observation
 * @property integer $users_id
 * @property integer $work_histories_p_id
 */
class FamilyPensioner extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_family';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'gender',
        'birth_date',
        'type',
        'state',
        'observation',
        'users_id',
        'work_histories_p_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'gender' => 'string',
        'type' => 'string',
        'state' => 'string',
        'observation' => 'string',
        'users_id' => 'integer',
        'work_histories_p_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'gender' => 'nullable|string|max:240',
        'birth_date' => 'nullable',
        'type' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:45',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

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
