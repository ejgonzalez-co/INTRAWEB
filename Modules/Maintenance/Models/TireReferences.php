<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireReferences
 * @package Modules\Maintenance\Models
 * @version November 25, 2021, 11:07 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $mantResumeMachineryVehiclesYellows
 * @property \Illuminate\Database\Eloquent\Collection $mantResumeMachineryVehiclesYellow1s
 * @property string $name
 */
class TireReferences extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_all';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantResumeMachineryVehiclesYellows() {
        return $this->hasMany(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_tire_all_front');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantResumeMachineryVehiclesYellow1s() {
        return $this->hasMany(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_tire_all_rear');
    }
}
