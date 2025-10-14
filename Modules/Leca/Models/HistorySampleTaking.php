<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistorySampleTaking
 * @package Modules\Leca\Models
 * @version January 21, 2022, 5:48 pm -05
 *
 * @property Modules\Leca\Models\LcSampleTaking $lcSampleTaking
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_sample_taking_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $action
 * @property string $observation
 */
class HistorySampleTaking extends Model
{
    use SoftDeletes;

    public $table = 'lc_history_sample_taking';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_sample_taking_id',
        'users_id',
        'user_name',
        'action',
        'observation',
        'consecutivo',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_sample_taking_id' => 'integer',
        'users_id' => 'integer',
        'user_name' => 'string',
        'action' => 'string',
        'observation' => 'string',
        'consecutivo' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_sample_taking_id' => 'required',
        'users_id' => 'required',
        'user_name' => 'nullable|string|max:255',
        'consecutivo' => 'nullable|string|max:255',
        'action' => 'nullable|string|max:255',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTaking()
    {
        return $this->belongsTo(\Modules\Leca\Models\LcSampleTaking::class, 'lc_sample_taking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
