<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesEspectro
 * @package Modules\leca\Models
 * @version August 31, 2022, 3:54 pm -05
 *
 * @property Modules\leca\Models\LcEnsayoEspectro $lcEnsayoEspectro
 * @property Modules\leca\Models\User $users
 * @property integer $users_id
 * @property string $ensayo
 * @property string $name_user
 * @property string $observation
 * @property integer $lc_ensayo_espectro_id
 */
class ObservacionesEspectro extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_espectro';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'ensayo',
        'name_user',
        'observation',
        'lc_ensayo_espectro_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'ensayo' => 'string',
        'name_user' => 'string',
        'observation' => 'string',
        'lc_ensayo_espectro_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_user' => 'nullable|string|max:120',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoEspectro() {
        return $this->belongsTo(\Modules\leca\Models\EnsayoEspectro::class, 'lc_ensayo_espectro_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }
}
