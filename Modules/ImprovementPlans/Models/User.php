<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * @package Modules\ImprovementPlans\Models
 * @version August 25, 2023, 8:51 am -05
 *
 * @property \Modules\ImprovementPlans\Models\Dependencia $idDependencia
 * @property \Modules\ImprovementPlans\Models\Cargo $idCargo
 * @property \Illuminate\Database\Eloquent\Collection $asHistories
 * @property \Illuminate\Database\Eloquent\Collection $labInputManagements
 * @property \Illuminate\Database\Eloquent\Collection $labPhysicalSpaces
 * @property \Illuminate\Database\Eloquent\Collection $labPhysicalSpace1s
 * @property \Illuminate\Database\Eloquent\Collection $labPhysicalSpace2s
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
 * @property boolean $block
 * @property boolean $sendEmail
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
 * @property integer $user_joomla_id
 */
class User extends Model
{
        use SoftDeletes;

    public $table = 'users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'id',
        'id_cargo',
        'id_dependencia',
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
        'cf_user_id',
        'user_joomla_id'
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
        'block' => 'boolean',
        'sendEmail' => 'boolean',
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
        'cf_user_id' => 'integer',
        'user_joomla_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_cargo' => 'nullable',
        'id_dependencia' => 'nullable',
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'email_verified_at' => 'nullable',
        'password' => 'required|string|max:255',
        'remember_token' => 'nullable|string|max:100',
        'url_img_profile' => 'nullable|string|max:100',
        'url_digital_signature' => 'nullable|string|max:100',
        'username' => 'nullable|string|max:255',
        'block' => 'nullable|boolean',
        'sendEmail' => 'nullable|boolean',
        'lastvisitDate' => 'nullable',
        'registerDate' => 'nullable',
        'lastResetTime' => 'nullable',
        'resetCount' => 'nullable|integer',
        'logapp' => 'nullable|string|max:45',
        'certificatecrt' => 'nullable|string|max:45',
        'certificatekey' => 'nullable|string|max:45',
        'certificatepfx' => 'nullable|string|max:45',
        'localcertificatecrt' => 'nullable|string|max:45',
        'localcertificatekey' => 'nullable|string|max:45',
        'localcertificatepfx' => 'nullable|string|max:45',
        'cf_user_id' => 'nullable',
        'user_joomla_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idDependencia() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\UserOtherDependence::class, 'id_dependencia',);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idCargo() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\Cargo::class, 'id_cargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function asHistories() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\AsHistory::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function labInputManagements() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\LabInputManagement::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function labPhysicalSpaces() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\LabPhysicalSpace::class, 'user_director');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function labPhysicalSpace1s() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\LabPhysicalSpace::class, 'user_coordinator');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function labPhysicalSpace2s() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\LabPhysicalSpace::class, 'user_technical');
    }
}
