<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoTurbidez
 * @package Modules\Leca\Models
 * @version August 3, 2022, 2:36 pm -05
 *
 * @property Modules\Leca\Models\LcEnsayoTurbidez $lcEnsayoTurbidez
 * @property Modules\Leca\Models\User $users
 * @property integer $users_id
 * @property string $name_user
 * @property string $observation
 * @property integer $lc_ensayo_turbidez_id
 */
class ObservacionesDuplicadoTurbidez extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_turbidez';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_turbidez_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'name_user' => 'string',
        'observation' => 'string',
        'lc_ensayo_turbidez_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_user' => 'nullable|string|max:120',
        
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoTurbidez() {
        return $this->belongsTo(\Modules\Leca\Models\EnsayoTurbidez::class, 'lc_ensayo_turbidez_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}