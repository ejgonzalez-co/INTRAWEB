<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;

/**
 * Class ExternalRecipient
 * @package Modules\Correspondence\Models
 * @version January 19, 2022, 3:30 pm -05
 *
 * @property Modules\Correspondence\Models\Cargo $cargos
 * @property Modules\Correspondence\Models\Dependencia $dependencias
 * @property Modules\Correspondence\Models\WorkGroup $workGroups
 * @property Modules\Correspondence\Models\CorrespondenceExternal $correspondenceExternal
 * @property Modules\Intranet\Models\User $users
 * @property string $type
 * @property boolean $is_readed
 * @property string $times
 * @property integer $correspondence_external_id
 * @property integer $users_id
 * @property integer $work_groups_id
 * @property integer $cargos_id
 * @property integer $dependencias_id
 */
class ExternalRecipient extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_external_recipient';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'type',
        'is_readed',
        'times',
        'correspondence_external_id',
        'users_id',
        'work_groups_id',
        'cargos_id',
        'dependencias_id',
        'name'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'is_readed' => 'boolean',
        'times' => 'string',
        'correspondence_external_id' => 'integer',
        'users_id' => 'integer',
        'work_groups_id' => 'integer',
        'cargos_id' => 'integer',
        'dependencias_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'nullable|string|max:45',
        'is_readed' => 'nullable|boolean',
        'times' => 'nullable|string',
        'correspondence_external_id' => 'required',
        'users_id' => 'nullable',
        'work_groups_id' => 'nullable',
        'cargos_id' => 'nullable',
        'dependencias_id' => 'nullable'
    ];


    //Contador de rebotes relacionados a la correspondencia 
    public function getCountReboundsAttribute() {
        $count_notifications = NotificacionesMailIntraweb::where('consecutivo', $this->consecutive)
        ->where(function($query) {
            $query->where('estado_notificacion', 'Rebote')
                ->orWhere('estado_notificacion', 'No entregado');
        })
        ->count();
        return $count_notifications;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargos() {
        return $this->belongsTo(\Modules\Correspondence\Models\Cargo::class, 'cargos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Correspondence\Models\Dependencia::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workGroups() {
        return $this->belongsTo(\Modules\Correspondence\Models\WorkGroup::class, 'work_groups_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function correspondenceExternal() {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceExternal::class, 'correspondence_external_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
