<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoHierro
 * @package Modules\Leca\Models
 * @version June 16, 2022, 11:22 am -05
 *
 * @property Modules\Leca\Models\LcEnsayoHierro $lcEnsayoHierro
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_ensayo_hierro_id
 * @property integer $users_id
 * @property string $name_user
 * @property string $observation
 */
class ObservacionesDuplicadoHierro extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_hierro';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    

    public $fillable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_hierro_id'
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
        'lc_ensayo_hierro_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'lc_ensayo_hierro_id' => 'required'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoHierro() {
        return $this->belongsTo(\Modules\Leca\Models\EnsayoHierro::class, 'lc_ensayo_hierro_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
