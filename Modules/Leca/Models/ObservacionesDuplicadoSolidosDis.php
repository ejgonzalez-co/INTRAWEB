<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoSolidosDis
 * @package Modules\leca\Models
 * @version December 19, 2022, 11:30 am -05
 *
 * @property Modules\leca\Models\LcEnsayoSolidosDi $lcEnsayoSolidosDis
 * @property Modules\leca\Models\User $users
 * @property integer $lc_ensayo_solidos_dis_id
 * @property integer $users_id
 * @property string $name_user
 * @property string $observation
 */
class ObservacionesDuplicadoSolidosDis extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_solidos_dis';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_ensayo_solidos_dis_id',
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
        'lc_ensayo_solidos_dis_id' => 'integer',
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
        'lc_ensayo_solidos_dis_id' => 'required|integer',
        // 'users_id' => 'required',
        'name_user' => 'nullable|string|max:120',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoSolidosDis() {
        return $this->belongsTo(\Modules\leca\Models\LcEnsayoSolidosDi::class, 'lc_ensayo_solidos_dis_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }
}
