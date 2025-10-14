<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireWearHistory
 * @package Modules\Maintenance\Models
 * @version June 10, 2022, 2:43 pm -05
 *
 * @property Modules\Maintenance\Models\MantTireWear $mantTireWears
 * @property integer $mant_tire_wears_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $action
 * @property string $plaque
 * @property string $position
 * @property number $revision_pressure
 * @property number $revision_mileage
 * @property number $wear_total
 * @property string $status
 * @property string $observation
 * @property string $descripcion
 */
class TireWearHistory extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_wear_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_tire_wears_id',
        'mant_tire_informations_id',
        'users_id',
        'user_name',
        'action',
        'plaque',
        'position',
        'revision_pressure',
        'revision_mileage',
        'wear_total',
        'status',
        'observation',
        'descripcion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_tire_wears_id' => 'integer',
        'mant_tire_informations_id' => 'integer',
        'users_id' => 'integer',
        'user_name' => 'string',
        'action' => 'string',
        'plaque' => 'string',
        'position' => 'string',
        'revision_pressure' => 'float',
        'revision_mileage' => 'float',
        'wear_total' => 'float',
        'status' => 'string',
        'observation' => 'string',
        'descripcion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'mant_tire_wears_id' => 'required',
        'users_id' => 'nullable',
        'user_name' => 'nullable|string|max:255',
        'action' => 'nullable|string|max:255',
        'plaque' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'revision_pressure' => 'nullable|numeric',
        'revision_mileage' => 'nullable|numeric',
        'wear_total' => 'nullable|numeric',
        'status' => 'nullable|string|max:45',
        'observation' => 'nullable|string',
        'descripcion' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function TireWears() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireWear::class, 'mant_tire_wears_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tireInformations() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireInformations::class, 'mant_tire_informations_id');
    }
}
