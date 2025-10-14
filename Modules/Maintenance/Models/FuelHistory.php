<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FuelHistory
 * @package Modules\maintenance\Models
 * @version February 8, 2022, 3:33 pm -05
 *
 * @property Modules\maintenance\Models\User $users
 * @property integer $users_id
 * @property string $description
 * @property string $plaque
 * @property string $user_name
 * @property integer $id_fuel
 * @property string $action
 */
class FuelHistory extends Model {
    use SoftDeletes;

    public $table = 'mant_fuels_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'description',
        'plaque',
        'user_name',
        'id_fuel',
        'action',
        'date_register'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'description' => 'string',
        'plaque' => 'string',
        'user_name' => 'string',
        'id_fuel' => 'integer',
        'action' => 'string',
        'date_register' => 'string'
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'description' => 'nullable|string|max:255',
        'plaque' => 'nullable|string|max:255',
        'date_register' => 'nullable|string|max:255',
        'user_name' => 'nullable|string|max:255',
        'id_fuel' => 'nullable',
        'action' => 'nullable|string|max:255',
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
