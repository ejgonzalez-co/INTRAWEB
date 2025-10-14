<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PollUser
 * @package Modules\Intranet\Models
 * @version June 27, 2021, 4:01 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetPoll $intranetPolls
 * @property \Modules\Intranet\Models\User $users
 * @property string $users_name
 * @property integer $users_id
 * @property integer $intranet_polls_id
 */
class PollUser extends Model
{
        //use SoftDeletes;

    public $table = 'intranet_polls_users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_name',
        'users_id',
        'intranet_polls_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_name' => 'string',
        'users_id' => 'integer',
        'intranet_polls_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_name' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'

    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetPolls() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetPoll::class, 'intranet_polls_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    // public function users() {
    //     return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    // }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }
}
