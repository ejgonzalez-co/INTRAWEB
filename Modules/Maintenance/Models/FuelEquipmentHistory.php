<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FuelEquipmentHistory
 * @package Modules\maintenance\Models
 * @version February 14, 2022, 8:29 am -05
 *
 * @property Modules\maintenance\Models\User $users
 * @property integer $users_id
 * @property string $date_register
 * @property string $description
 * @property string $action
 * @property string $name_user
 * @property string $dependencia
 * @property string $id_equipment_fuel
 */
class FuelEquipmentHistory extends Model {
    use SoftDeletes;

    public $table = 'mant_equipment_fuels_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'date_register',
        'description',
        'action',
        'name_user',
        'dependencia',
        'id_equipment_fuel'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'date_register' => 'string',
        'description' => 'string',
        'action' => 'string',
        'name_user' => 'string',
        'dependencia' => 'string',
        'id_equipment_fuel' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'date_register' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:255',
        'action' => 'nullable|string|max:255',
        'name_user' => 'nullable|string|max:255',
        'dependencia' => 'nullable|string|max:255',
        'id_equipment_fuel' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
