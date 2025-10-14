<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoMicro
 * @package Modules\Leca\Models
 * @version October 19, 2022, 5:43 pm -05
 *
 * @property Modules\Leca\Models\LcEnsayosMicrobiologico $lcEnsayosMicrobiologicos
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_ensayos_microbiologicos_id
 * @property integer $users_id
 * @property string $ensayo
 * @property string $name_user
 * @property string $observation
 */
class ObservacionesDuplicadoMicro extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_micro';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_ensayos_microbiologicos_id',
        'users_id',
        'ensayo',
        'name_user',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_ensayos_microbiologicos_id' => 'integer',
        'users_id' => 'integer',
        'ensayo' => 'string',
        'name_user' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_ensayos_microbiologicos_id' => 'required|integer',
        'users_id' => 'required',
        'ensayo' => 'nullable|string|max:120',
        'name_user' => 'nullable|string|max:120',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayosMicrobiologicos() {
        return $this->belongsTo(\Modules\Leca\Models\EnsayosMicrobiologico::class, 'lc_ensayos_microbiologicos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
