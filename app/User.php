<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail;
use DateTimeInterface;
use App\Http\Controllers\SendNotificationController;
use App\Http\Controllers\AppBaseController;


class User extends Authenticatable implements Auditable, MustVerifyEmail
{
    use Notifiable, HasRoles, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guard_name = 'web';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'sedes_id' => 'integer',
        'id_cargo' => 'integer',
        'id_dependencia' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'second_password' => 'string',
        'remember_token' => 'string',
        'url_img_profile' => 'string',
        'url_digital_signature' => 'string',
        'username' => 'string',
        'block' => 'integer',
        'autorizado_firmar' => 'integer',
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
        'localcertificatepfx' => 'string',
        'enable_second_password' => 'boolean',
        'funcionario_encargado' => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_cargo',
        'id_dependencia',
        'name',
        'email',
        'email_verified_at',
        'remember_token',
        'url_img_profile',
        'url_digital_signature',
        'password',
        'second_password',
        'enable_second_password',
        'username',
        'block',
        'sendEmail',
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
        'user_joomla_id',
        'accept_service_terms',
        'user_type',
        'autorizado_firmar',
        'sedes_id',
        'is_assignable_pqr_correspondence',
        'funcionario_encargado',
        'fecha_inicio_encargo',
        'fecha_fin_encargo',
        'version_actual',
        'escala_firma'
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'position_id',
        'name',
        'email',
        'url_img_profile',
        'url_digital_signature',
        'uuid',
        'state'
    ];

    protected $appends = ['fullname','users_id'];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'password'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
        'password',
        'second_password',
        'remember_token',
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
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rulesUpdate = [
        'id_cargo' => 'required',
        'id_dependencia' => 'required',
        'name' => 'required|min:3|max:50',
        'email' => 'required|email',
    ];


    public static $rulesAPILogin = [
        'email' => 'required|email',
        'password' => 'required|min:8'
    ];

    public function generateToken() {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }

    /**
     * Sobrescribe la notificacion de rstablecimiento de contrasena
     */
    public function sendPasswordResetNotification($token) {
        // $this->notify(new CustomResetPassword($token, $this->name));

        try{
            $notificacion = [
                'token' => $token,
                'nameUser' => $this->name
            ];
            $asunto = json_decode('{"subject": "Aviso para restablecer contraseña"}');
            SendNotificationController::SendNotification('intranet::mail.reset_password',$asunto,$notificacion,$this->email,'Login');

        }catch (\Exception $error) {
            // Envia mensaje de error
            AppBaseController::generateSevenLog('userControllerEmail', 'App\Http\Controllers\ControllerRegisterController - '. ($user ? $user->name : 'System').' -  Error: '.$error->getMessage());
        }
    }

    /**
     * Sobrescribe la notificacion de verificacion de cuenta
     */
    public function sendEmailVerificationNotification() {
        $this->notify(new CustomVerifyEmail($this->name));
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function usersHistory()
    {
        return $this->hasMany(\Modules\Intranet\Models\UserHistory::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function workGroups()
    {
        return $this->belongsToMany(\Modules\Intranet\Models\WorkGroup::class, 'users_work_groups', 'users_id', 'work_groups_id')
            ->withPivot('admin', 'state');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function assetCreateAuthorization()
    {
        return $this->hasMany(\Modules\Maintenance\Models\AssetCreateAuthorization::class, 'responsable');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function citizens() {
        return $this->belongsTo(\Modules\Intranet\Models\Citizen::class, 'user_id');
    }


    /**
     * Obtener el atributo nombre completo para el usuario.
     *
     * Este método devuelve el nombre completo del usuario incluyendo su cargo y dependencia.
     *
     * @return string
     */
    public function getFullnameAttribute()
    {
        // Obtener el nombre del cargo. Si no se ha asignado un cargo, se utiliza 'Sin cargo' por defecto.
        $positionNombre = $this->positions ? $this->positions->nombre : 'Sin cargo';

        // Obtener el nombre de la dependencia. Si no se ha asignado una dependencia, se utiliza 'Sin dependencia' por defecto.
        $dependencyNombre = $this->dependencies ? $this->dependencies->nombre : 'Sin dependencia';

        // Combinar el nombre, cargo y dependencia en el nombre completo.
        return "{$this->name} ({$positionNombre}, {$dependencyNombre})";
    }

    public function sede() {
        return $this->belongsTo(\Modules\Intranet\Models\Headquarter::class, 'sedes_id');
    }

    public function getUsersIdAttribute()
    {

        return $this->id;
    }


}
