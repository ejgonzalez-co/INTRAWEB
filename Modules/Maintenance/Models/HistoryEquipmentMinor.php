<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryEquipmentMinor
 * @package Modules\Maintenance\Models
 * @version August 27, 2021, 2:32 pm -05
 *
 * @property Modules\Maintenance\Models\Dependencia $dependencias
 * @property Modules\Maintenance\Models\MantMinorEquipmentFuel $mantMinorEquipmentFuel
 * @property Modules\Maintenance\Models\User $users
 * @property integer $mant_minor_equipment_fuel_id
 * @property integer $dependencias_id
 * @property integer $users_id
 * @property string $name
 * @property string $position
 * @property string $approved_process
 * @property string $create
 * @property string $update
 * @property string $process_leader_name
 */
class HistoryEquipmentMinor extends Model {
    use SoftDeletes;

    public $table = 'mant_history_minor_equipment';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_minor_equipment_fuel_id',
        'dependencias_id',
        'users_id',
        'name',
        'position',
        'approved_process',
        'create',
        'update',
        'process_leader_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_minor_equipment_fuel_id' => 'integer',
        'dependencias_id' => 'integer',
        'users_id' => 'integer',
        'name' => 'string',
        'position' => 'string',
        'approved_process' => 'string',
        'create' => 'string',
        'update' => 'string',
        'process_leader_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_minor_equipment_fuel_id' => 'required',
        'dependencias_id' => 'required',
        'users_id' => 'required',
        'name' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'approved_process' => 'nullable|string|max:255',
        'create' => 'nullable|string|max:255',
        'update' => 'nullable|string|max:255',
        'process_leader_name' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

       /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciasApproved() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'approved_process');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantMinorEquipmentFuel() {
        return $this->belongsTo(\Modules\Maintenance\Models\MinorEquipmentFuel::class, 'mant_minor_equipment_fuel_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }


}
