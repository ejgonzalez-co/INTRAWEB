<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoConductividad
 * @package Modules\leca\Models
 * @version February 1, 2023, 12:03 pm -05
 *
 * @property Modules\leca\Models\LcEnsayoConductividad $lcEnsayoConductividad
 * @property Modules\leca\Models\User $users
 * @property integer $lc_ensayo_conductividad_id
 * @property string $name_user
 * @property string $observation
 * @property integer $users_id
 */
class ObservacionesDuplicadoConductividad extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_conductividad';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_ensayo_conductividad_id',
        'name_user',
        'observation',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_ensayo_conductividad_id' => 'integer',
        'name_user' => 'string',
        'observation' => 'string',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_ensayo_conductividad_id' => 'required',
        'name_user' => 'nullable|string|max:120',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoConductividad() {
        return $this->belongsTo(\Modules\leca\Models\LcEnsayoConductividad::class, 'lc_ensayo_conductividad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }
}
