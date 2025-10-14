<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoSolidosSecos
 * @package Modules\leca\Models
 * @version December 19, 2022, 5:25 pm -05
 *
 * @property Modules\leca\Models\LcEnsayoSolidosSeco $lcEnsayoSolidosSecos
 * @property Modules\leca\Models\User $users
 * @property integer $lc_ensayo_solidos_secos_id
 * @property integer $users_id
 * @property string $name_user
 * @property string $observation
 */
class ObservacionesDuplicadoSolidosSecos extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_solidos_secos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_ensayo_solidos_secos_id',
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
        'lc_ensayo_solidos_secos_id' => 'integer',
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
        'lc_ensayo_solidos_secos_id' => 'required|integer',
        // 'users_id' => 'required',
        'name_user' => 'nullable|string|max:120',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoSolidosSecos() {
        return $this->belongsTo(\Modules\leca\Models\LcEnsayoSolidosSeco::class, 'lc_ensayo_solidos_secos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }
}
