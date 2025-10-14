<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TireGestionHistory
 * @package Modules\maintenance\Models
 * @version February 15, 2022, 2:35 pm -05
 *
 * @property Modules\maintenance\Models\User $users
 * @property integer $users_id
 * @property string $action
 * @property string $description
 * @property string $name_user
 * @property string $plaque
 * @property string $dependencia
 * @property string $equipment
 */
class TireGestionHistory extends Model {
    use SoftDeletes;

    public $table = 'mant_tire_gestion_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'action',
        'description',
        'name_user',
        'plaque',
        'dependencia',
        'equipment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'action' => 'string',
        'description' => 'string',
        'name_user' => 'string',
        'plaque' => 'string',
        'dependencia' => 'string',
        'equipment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'action' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:255',
        'name_user' => 'nullable|string|max:255',
        'plaque' => 'nullable|string|max:255',
        'dependencia' => 'nullable|string|max:255',
        'equipment' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }
}
