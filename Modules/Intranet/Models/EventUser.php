<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EventUser
 * @package Modules\Intranet\Models
 * @version September 9, 2021, 12:54 am -05
 *
 * @property \Modules\Intranet\Models\IntranetEvent $intranetEvents
 * @property \Modules\Intranet\Models\User $users
 * @property string $users_name
 * @property integer $intranet_events_id
 * @property boolean $confirmation
 */
class EventUser extends Model
{
        use SoftDeletes;

    public $table = 'intranet_users_events';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'users_name',
        'intranet_events_id',
        'confirmation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'users_id' => 'integer',
        'users_name' => 'string',
        'intranet_events_id' => 'integer',
        'confirmation' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_name' => 'nullable|string|max:120',
        'intranet_events_id' => 'required',
        'confirmation' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetEvents() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetEvent::class, 'intranet_events_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }
}
