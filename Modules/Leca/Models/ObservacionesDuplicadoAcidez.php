<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoAcidez
 * @package Modules\Leca\Models
 * @version July 27, 2022, 3:45 pm -05
 *
 * @property Modules\Leca\Models\LcEnsayoAcidez $lcEnsayoAcidez
 * @property Modules\Leca\Models\User $users
 * @property integer $users_id
 * @property integer $lc_ensayo_acidez_id
 * @property string $name_user
 * @property string $observation
 */
class ObservacionesDuplicadoAcidez extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_acidez';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'lc_ensayo_acidez_id',
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
        'users_id' => 'integer',
        'lc_ensayo_acidez_id' => 'integer',
        'name_user' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoAcidez() {
        return $this->belongsTo(\Modules\Leca\Models\EnsayoAcidez::class, 'lc_ensayo_acidez_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
