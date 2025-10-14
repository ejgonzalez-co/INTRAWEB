<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

/**
 * [NO SE USA, ACTIVO ACTUALMENTE EL MODELO User POR DEFECTO]
 * Class User
 * @package Modules\Intranet\Models
 * @version June 17, 2020, 3:12 pm UTC
 *
 * @property Modules\Intranet\Models\Dependencia $idDependencia
 * @property Modules\Intranet\Models\Cargo $idCargo
 * @property \Illuminate\Database\Eloquent\Collection $permissionUsers
 * @property \Illuminate\Database\Eloquent\Collection $roleUsers
 * @property \Illuminate\Database\Eloquent\Collection $workGroups
 * @property integer $id_cargo
 * @property integer $id_dependencia
 * @property string $name
 * @property string $email
 * @property string|\Carbon\Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $url_img_profile
 * @property string $url_digital_signature
 * @property string $username
 * @property integer $block
 * @property integer $sendEmail
 * @property string|\Carbon\Carbon $lastvisitDate
 * @property string|\Carbon\Carbon $registerDate
 * @property string|\Carbon\Carbon $lastResetTime
 * @property integer $resetCount
 * @property string $logapp
 * @property string $certificatecrt
 * @property string $certificatekey
 * @property string $certificatepfx
 * @property string $localcertificatecrt
 * @property string $localcertificatekey
 * @property string $localcertificatepfx
 */
class User extends Authenticatable implements Auditable, MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasRoles, \OwenIt\Auditing\Auditable;

    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guard_name = 'web';

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at',
        'password',
        'remember_token',
        'cf_user_id'
    ];


    public $fillable = [
        'id_cargo',
        'id_dependencia',
        'name',
        'email',
        'email_verified_at',
        'remember_token',
        'url_img_profile',
        'url_digital_signature',
        'username',
        'block',
        'sendEmail',
        'lastvisitDate',
        'registerDate',
        'lastResetTime',
        'resetCount',
        'logapp',
        'certificatecrt',
        'certificatekey',
        'certificatepfx',
        'localcertificatecrt',
        'localcertificatekey',
        'localcertificatepfx',
        'cf_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_cargo' => 'integer',
        'id_dependencia' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'url_img_profile' => 'string',
        'url_digital_signature' => 'string',
        'username' => 'string',
        'block' => 'integer',
        'sendEmail' => 'integer',
        'lastvisitDate' => 'datetime',
        'registerDate' => 'datetime',
        'lastResetTime' => 'datetime',
        'resetCount' => 'integer',
        'logapp' => 'string',
        'certificatecrt' => 'string',
        'certificatekey' => 'string',
        'certificatepfx' => 'string',
        'localcertificatecrt' => 'string',
        'localcertificatekey' => 'string',
        'localcertificatepfx' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_cargo' => 'required',
        'id_dependencia' => 'required',
        'name' => 'required|min:3|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rulesUpdate = [
        'id_cargo' => 'required',
        'id_dependencia' => 'required',
        'name' => 'required|min:3|max:255',
        'email' => 'required|email',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencies() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'id_dependencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function positions() {
        return $this->belongsTo(\Modules\Intranet\Models\Position::class, 'id_cargo');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function workGroups()
    {
        return $this->belongsToMany(\Modules\Intranet\Models\WorkGroup::class, 'users_work_groups', 'users_id', 'work_groups_id')
            ->withPivot('admin', 'state');
    }
}
