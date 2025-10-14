<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkHistoriesNovelty
 * @package Modules\Workhistories\Models
 * @version July 30, 2021, 2:34 pm -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property Modules\Workhistories\Models\WorkHistory $workHistories
 * @property integer $users_id
 * @property integer $work_histories_id
 * @property string $user_name
 * @property string $type_novelty
 * @property string $description
 * @property string $attached
 */
class WorkHistoriesNovelty extends Model
{
        use SoftDeletes;

    public $table = 'work_histories_novelties';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'work_histories_id',
        'user_name',
        'type_novelty',
        'description',
        'attached'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'work_histories_id' => 'integer',
        'user_name' => 'string',
        'type_novelty' => 'string',
        'description' => 'string',
        'attached' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'work_histories_id' => 'required',
        'user_name' => 'nullable|string|max:120',
        'type_novelty' => 'nullable|string|max:80',
        'description' => 'nullable|string',
        'attached' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistories() {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistory::class, 'work_histories_id');
    }
}
