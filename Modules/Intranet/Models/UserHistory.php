<?php

namespace Modules\Intranet\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserHistory
 * @package Modules\Intranet\Models
 * @version July 27, 2020, 9:05 am -05
 *
 * @property \Modules\Intranet\Models\Cargo $cargos
 * @property \Modules\Intranet\Models\Dependencia $dependencias
 * @property \Modules\Intranet\Models\User $users
 * @property integer $users_id
 * @property integer $cargos_id
 * @property integer $dependencias_id
 * @property string $observation
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
 * @property integer $cf_user_id
 */
class UserHistory extends Model
{
    use SoftDeletes;

    public $table = 'users_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'users_id',
        'id_cargo',
        'id_dependencia',
        'observation',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'url_img_profile',
        'url_digital_signature',
        'username',
        'block',
        'sendEmail',
        'roles',
        'work_groups',
        'change_user',
        'lastvisitDate',
        'registerDate',
        'lastResetTime',
        'resetCount',
        'logapp',
        'birthday',
        'contract_start',
        'contract_end',
        'certificatecrt',
        'certificatekey',
        'certificatepfx',
        'localcertificatecrt',
        'localcertificatekey',
        'localcertificatepfx',
        'cf_user_id',
        'enable_second_password',
        'second_password',
        'accept_service_terms',
        'autorizado_firmar',
        'sedes_id',
        'funcionario_encargado',
        'fecha_inicio_encargo',
        'fecha_fin_encargo'


    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'id_cargo' => 'integer',
        'id_dependencia' => 'integer',
        'observation' => 'string',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'url_img_profile' => 'string',
        'url_digital_signature' => 'string',
        'username' => 'string',
        'block' => 'integer',
        'autorizado_firmar' => 'integer',
        'sendEmail' => 'integer',
        'roles' => 'string',
        'work_groups' => 'string',
        'change_user' => 'integer',
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
        'localcertificatepfx' => 'string',
        'enable_second_password' => 'integer',
        'cf_user_id' => 'integer',
        'funcionario_encargado' => 'integer'
    ];

    protected $appends = [
        'roles',
        'work_groups',
        'position',
        'dependency'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'name' => 'required',
        'email' => 'required'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Obtiene los Roles del hstorial en un arreglo
     */
    public function getRolesAttribute() {
        return json_decode($this->attributes['roles']);
    }

    /**
     * Obtiene los grupos de trabajo del hstorial en un arreglo
     */
    public function getWorkGroupsAttribute() {
        return json_decode($this->attributes['work_groups']);
    }

    /**
     * Obtiene el nombre del cargo
     */
    public function getPositionAttribute() {
        return $this->positions->nombre ?? '';
    }
    /**
     * Obtiene el nombre de la dependencia
     */
    public function getDependencyAttribute() {
        return $this->dependencies->nombre ?? '';
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
