<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireQuantitites
 * @package Modules\Maintenance\Models
 * @version September 4, 2021, 11:59 am -05
 *
 * @property Modules\Maintenance\Models\MantResumeMachineryVehiclesYellow $mantResumeMachineryVehiclesYellow
 * @property \Illuminate\Database\Eloquent\Collection $mantTireHistories
 * @property \Illuminate\Database\Eloquent\Collection $manttireInformations
 * @property integer $mant_resume_machinery_vehicles_yellow_id
 * @property integer $dependencias_id
 * @property string $plaque
 * @property integer $tire_quantity
 */
class TireQuantitites extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_quantities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_resume_machinery_vehicles_yellow_id',
        'dependencias_id',
        'plaque',
        'tire_quantity'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_resume_machinery_vehicles_yellow_id' => 'integer',
        'dependencias_id' => 'integer',
        'plaque' => 'string',
        'tire_quantity' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'mant_resume_machinery_vehicles_yellow_id' => 'required|integer',
        'dependencias_id' => 'required',
        'plaque' => 'nullable|string|max:255',
        'tire_quantity' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    **/
    public function dependencies()
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ResumeMachineryVehiclesYellow() {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_resume_machinery_vehicles_yellow_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tireInformations() {
        return $this->hasMany(\Modules\Maintenance\Models\TireInformations::class, 'mant_tire_quantities_id');
    }
}
