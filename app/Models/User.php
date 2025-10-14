<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Officer
 * @package App\Models
 * @version June 2, 2020, 2:27 pm UTC
 *
 * @property \App\Models\Position $position
 * @property \Illuminate\Database\Eloquent\Collection $permissionUsers
 * @property \Illuminate\Database\Eloquent\Collection $roleUsers
 * @property \Illuminate\Database\Eloquent\Collection $dependencies
 * @property \Illuminate\Database\Eloquent\Collection $workGroups
 * @property integer $position_id
 * @property string $name
 * @property string $email
 * @property string|\Carbon\Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $url_img_profile
 * @property string $url_digital_signature
 * @property string $uuid
 * @property boolean $state
 */
class User extends Authenticatable implements MustVerifyEmail, Auditable {
    use Notifiable, SoftDeletes, HasRoles, \OwenIt\Auditing\Auditable;

    protected $guard_name = 'web';

    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'password'
    ];


    public $fillable = [
        'position_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'url_img_profile',
        'url_digital_signature',
        'uuid',
        'state',
        'autorizado_firmar',
        'sedes_id',
        'ultima_conexion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'sedes_id' => 'integer',
        'position_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'url_img_profile' => 'string',
        'url_digital_signature' => 'string',
        'uuid' => 'string',
        'state' => 'boolean',
        'ultima_conexion' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'email' => 'required',
        'password' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function position()
    {
        return $this->belongsTo(\App\Models\Position::class, 'position_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function permissionUsers()
    {
        return $this->hasMany(\App\Models\PermissionUser::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function dependencies()
    {
        return $this->belongsToMany(\App\Models\Dependency::class, 'users_dependencies');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function workGroups()
    {
        return $this->belongsToMany(\App\Models\WorkGroup::class, 'users_work_groups');
    }
}
