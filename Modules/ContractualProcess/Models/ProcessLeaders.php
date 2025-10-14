<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProcessLeaders
 * @package Modules\ContractualProcess\Models
 * @version December 1, 2020, 9:32 am -05
 *
 * @property Modules\ContractualProcess\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $needs
 * @property string $leader_name
 * @property string $name_process
 * @property integer $users_id
 */
class ProcessLeaders extends Model
{
    use SoftDeletes;

    public $table = 'pc_process_leaders';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'leader_name',
        'name_process',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'leader_name' => 'string',
        'name_process' => 'string',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function needs()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\Needs::class, 'pc_process_leaders_id');
    }
}
