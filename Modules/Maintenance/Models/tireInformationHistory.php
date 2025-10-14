<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class tireInformationHistory
 * @package Modules\Maintenance\Models
 * @version March 28, 2022, 11:21 am -05
 *
 * @property Modules\Maintenance\Models\MantTireQuantity $mantTireQuantities
 * @property Modules\Maintenance\Models\User $users
 * @property integer $users_id
 * @property integer $mant_tire_informations_id
 * @property string $user_name
 * @property string $action
 * @property string $observation
 * @property string $plaque
 * @property string $dependencia
 * @property string $number
 * @property string $position
 * @property string $brand
 */
class tireInformationHistory extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_information_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_tire_informations_id',
        'user_name',
        'action',
        'observation',
        'descripcion',
        'plaque',
        'dependencia',
        'number',
        'position',
        'brand',
        'code',
        'assignment_type',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_tire_informations_id' => 'integer',
        'user_name' => 'string',
        'action' => 'string',
        'observation' => 'string',
        'descripcion' => 'string',
        'plaque' => 'string',
        'dependencia' => 'string',
        'number' => 'string',
        'position' => 'string',
        'brand' => 'string',
        'code' => 'string',
        'assignment_type' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'mant_tire_informations_id' => 'required',
        'user_name' => 'nullable|string|max:255',
        'action' => 'nullable|string|max:255',
        'observation' => 'nullable|string',
        'descripcion' => 'nullable|string',
        'plaque' => 'nullable|string|max:255',
        'dependencia' => 'nullable|string|max:255',
        'number' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'code' => 'nullable|string|max:80',
        'assignment_type' => 'nullable|string|max:60',
        'status' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function TireInformations() {
        return $this->hasMany(\Modules\Maintenance\Models\TireInformations::class, 'mant_tire_informations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ResumeMachineryVehiclesYellow() {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_resume_machinery_vehicles_yellow_id');
    }
}
