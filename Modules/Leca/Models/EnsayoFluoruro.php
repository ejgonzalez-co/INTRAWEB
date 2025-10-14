<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoFluoruro
 * @package Modules\Leca\Models
 * @version December 16, 2022, 10:49 pm -05
 *
 * @property Modules\Leca\Models\LcFluoruro $lcFluoruros
 * @property Modules\Leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprFluoruros
 * @property \Illuminate\Database\Eloquent\Collection $lcObservacionesDuplicadoFluoruros
 * @property integer $lc_fluoruros_id
 * @property integer $lc_sample_taking_has_lc_list_trials_id
 * @property integer $users_id
 * @property string $volumen_muestra
 * @property string $mv
 * @property string $mg_fl
 * @property string $resultado_promedio
 * @property string $concentracion
 * @property string $consecutivo
 * @property string $vigencia
 * @property string $consecutivo_ensayo
 * @property string $estado
 * @property string $tipo
 * @property string $metodo
 * @property string $user_name
 * @property string $id_muestra
 * @property string $resultado_general
 * @property string $aprobar_resultado
 * @property string $aprobacion_usuario
 */
class EnsayoFluoruro extends Model {
    use SoftDeletes;

    public $table = 'lc_ensayo_fluoruros';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_fluoruros_id',
        'lc_sample_taking_has_lc_list_trials_id',
        'users_id',
        'volumen_muestra',
        'mv',
        'mg_fl',
        'duplicado',
        'resultado_promedio',
        'concentracion',
        'consecutivo',
        'vigencia',
        'consecutivo_ensayo',
        'estado',
        'tipo',
        'metodo',
        'user_name',
        'id_muestra',
        'resultado_general',
        'aprobar_resultado',
        'id_carta',
        'observacion_analista',
        'aprobacion_usuario'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_fluoruros_id' => 'integer',
        'lc_sample_taking_has_lc_list_trials_id' => 'integer',
        'users_id' => 'integer',
        'volumen_muestra' => 'string',
        'mv' => 'string',
        'resultado_promedio' => 'string',
        'concentracion' => 'float',
        'mg_fl' => 'float',
        'consecutivo' => 'string',
        'vigencia' => 'string',
        'consecutivo_ensayo' => 'string',
        'estado' => 'string',
        'tipo' => 'string',
        'metodo' => 'string',
        'user_name' => 'string',
        'id_muestra' => 'string',
        'resultado_general' => 'string',
        'aprobar_resultado' => 'string',
        'id_carta' => 'integer',
        'aprobacion_usuario' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'lc_fluoruros_id' => 'required',
        // 'lc_sample_taking_has_lc_list_trials_id' => 'required',
        // 'users_id' => 'required',
        'volumen_muestra' => 'nullable|string|max:255',
        'mv' => 'nullable|string|max:255',
        'mg_fl' => 'nullable|string|max:255',
        'resultado_promedio' => 'nullable|string|max:255',
        'concentracion' => 'nullable|string|max:255',
        'consecutivo' => 'nullable|string|max:255',
        'vigencia' => 'nullable|string|max:255',
        'consecutivo_ensayo' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:255',
        'tipo' => 'nullable|string|max:255',
        'metodo' => 'nullable|string|max:255',
        'user_name' => 'nullable|string|max:255',
        'id_muestra' => 'nullable|string|max:255',
        'resultado_general' => 'nullable|string|max:255',
        'aprobar_resultado' => 'nullable|string|max:255',
        'aprobacion_usuario' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcFluoruros() {
        return $this->belongsTo(\Modules\Leca\Models\LcFluoruro::class, 'lc_fluoruros_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTakingHasLcListTrials() {
        return $this->belongsTo(\Modules\Leca\Models\LcSampleTakingHasLcListTrial::class, 'lc_sample_taking_has_lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDprFluoruros() {
        return $this->hasMany(\Modules\Leca\Models\DprFluoruro::class, 'lc_ensayo_fluoruros_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcObservacionesDuplicadoFluoruros() {
        return $this->hasMany(\Modules\Leca\Models\LcObservacionesDuplicadoFluoruro::class, 'lc_ensayo_fluoruros_id');
    }
}
