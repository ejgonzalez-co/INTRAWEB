<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObsercacionesDuplicadoAlcalinidad
 * @package Modules\Leca\Models
 * @version July 25, 2022, 11:50 am -05
 *
 * @property Modules\Leca\Models\LcEnsayoAlcalinidad $lcEnsayoAlcalinidad
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_ensayo_alcalinidad_id
 * @property integer $users_id
 * @property string $real
 * @property string $add1
 * @property string $add2
 * @property string $tipo
 * @property string $consecutivo
 * @property string $consecutivo_ensayo
 * @property string $user_name
 * @property string $resultado
 * @property string $resultado_completo
 * @property string $user_id
 * @property string $volumen_adicionado
 * @property string $volumen_muestra
 * @property string $concentracion
 * @property string $id_ensayo
 * @property string $id_muestra
 */
class ObsercacionesDuplicadoAlcalinidad extends Model {
        use SoftDeletes;
    
        public $table = 'lc_observaciones_duplicado_alcalinidad';
        
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
    
        
        protected $dates = ['deleted_at'];
    
        
    
        public $fillable = [
            'users_id',
            'name_user',
            'observation',
            'lc_ensayo_alcalinidad_id'
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
            'lc_ensayo_alcalinidad_id' => 'integer'
        ];
    
        /**
         * Validation rules
         *
         * @var array
         */
        public static $rules = [
            
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
        ];

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoAlcalinidad() {
        return $this->belongsTo(\Modules\Leca\Models\EnsayoAlcalinidad::class, 'lc_ensayo_alcalinidad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
