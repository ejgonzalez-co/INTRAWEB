<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Family
 * @package Modules\Workhistories\Models
 * @version May 5, 2021, 2:23 pm -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property Modules\Workhistories\Models\WorkHistory $workHistories
 * @property string $name
 * @property string $gender
 * @property string $birth_date
 * @property string $type
 * @property integer $state
 * @property integer $users_id
 * @property integer $work_histories_id
 */
class Family extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_family';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'gender',
        'birth_date',
        'type',
        'state',
        'users_id',
        'work_histories_id',
        'observation'
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
        'users_id' => 'integer',
        'work_histories_id' => 'integer'
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
    public function workHistories()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistory::class, 'work_histories_id');
    }
}
