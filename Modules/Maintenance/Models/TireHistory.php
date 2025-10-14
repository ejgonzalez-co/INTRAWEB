<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireHistory
 * @package Modules\Maintenance\Models
 * @version September 8, 2021, 5:51 pm -05
 *
 * @property Modules\Maintenance\Models\MantTireQuantity $mantTireQuantities
 * @property Modules\Maintenance\Models\User $users
 * @property integer $users_id
 * @property integer $mant_tire_quantities_id
 * @property string $active_name
 * @property string $plaque
 * @property string $tire_reference
 * @property string $tire_position
 * @property string $tire_type
 * @property string $tire_brand
 * @property string $code_tire
 * @property string $status
 * @property string $observation
 */
class TireHistory extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'user_name',
        'mant_tire_informations_id',
        'active_name',
        'plaque',
        'tire_reference',
        'tire_position',
        'tire_type',
        'tire_brand',
        'code_tire',
        'status',
        'observation',
        'info'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'user_name'=>'string',
        'mant_tire_informations_id' => 'integer',
        'active_name' => 'string',
        'plaque' => 'string',
        'tire_reference' => 'string',
        'tire_position' => 'string',
        'tire_type' => 'string',
        'tire_brand' => 'string',
        'code_tire' => 'string',
        'status' => 'string',
        'observation' => 'string',
        'info' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'users_id' => 'required',
        // 'mant_tire_quantities_id' => 'required',
        'user_name'=>'nullable|string|max:150',
        'active_name' => 'nullable|string|max:150',
        'plaque' => 'nullable|string|max:100',
        'tire_reference' => 'nullable|string|max:100',
        'tire_position' => 'nullable|string|max:100',
        'tire_type' => 'nullable|string|max:100',
        'tire_brand' => 'nullable|string|max:100',
        'code_tire' => 'nullable|string|max:100',
        'status' => 'nullable|string|max:100',
        'observation' => 'nullable|string|max:100',
        'info' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tireInformations() {
        return $this->belongsTo(\Modules\Maintenance\Models\TireInformations::class, 'mant_tire_informations_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
