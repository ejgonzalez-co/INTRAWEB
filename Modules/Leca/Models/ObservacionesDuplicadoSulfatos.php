<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoSulfatos
 * @package Modules\leca\Models
 * @version December 18, 2022, 5:00 pm -05
 *
 * @property Modules\leca\Models\LcEnsayoSulfato $lcEnsayoSulfatos
 * @property Modules\leca\Models\User $users
 * @property integer $lc_ensayo_sulfatos_id
 * @property integer $users_id
 * @property string $name_user
 * @property string $observation
 */
class ObservacionesDuplicadoSulfatos extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_sulfato';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_ensayo_sulfatos_id',
        'users_id',
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
        'lc_ensayo_sulfatos_id' => 'integer',
        'users_id' => 'integer',
        'name_user' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_ensayo_sulfatos_id' => 'required|integer',
        // 'users_id' => 'required',
        'name_user' => 'nullable|string|max:120',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoSulfatos() {
        return $this->belongsTo(\Modules\leca\Models\LcEnsayoSulfato::class, 'lc_ensayo_sulfatos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }
}
