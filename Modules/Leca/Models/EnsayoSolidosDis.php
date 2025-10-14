<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoSolidosDis
 * @package Modules\Leca\Models
 * @version December 19, 2022, 8:29 am -05
 *
 * @property Modules\Leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\Leca\Models\LcSolido $lcSolidos
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprPrSolidosDis
 * @property \Illuminate\Database\Eloquent\Collection $lcObservacionesDuplicadoSolidosDis
 * @property integer $lc_solidos_id
 * @property integer $lc_sample_taking_has_lc_list_trials_id
 * @property integer $users_id
 * @property string $campo_a
 * @property string $campo_b
 * @property string $volumen_muestra
 * @property string $mgl
 * @property string $hr
 * @property string $centigrados
 * @property string $concentracion
 * @property string $resultado_promedio
 * @property string $consecutivo
 * @property string $vigencia
 * @property string $consecutivo_ensayo
 * @property string $estado
 * @property string $tipo
 * @property string $user_name
 * @property string $id_muestra
 * @property string $duplicado
 * @property string $metodo
 * @property string $id_carta
 * @property string $aprobar_resultado
 * @property string $aprobacion_usuario
 * @property string $observacion_analista
 */
class EnsayoSolidosDis extends Model {
    use SoftDeletes;

    public $table = 'lc_ensayo_solidos_dis';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        // 'lc_solidos_id',
        // 'lc_sample_taking_has_lc_list_trials_id',
        // 'users_id',
        'campo_a',
        'campo_b',
        'volumen_muestra',
        'mgl',
        'hr',
        'centigrados',
        'concentracion',
        'resultado_promedio',
        'consecutivo',
        'vigencia',
        'consecutivo_ensayo',
        'estado',
        'tipo',
        'user_name',
        'id_muestra',
        'duplicado',
        'metodo',
        'id_carta',
        'aprobar_resultado',
        'aprobacion_usuario',
        'observacion_analista'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_solidos_id' => 'integer',
        'lc_sample_taking_has_lc_list_trials_id' => 'integer',
        'users_id' => 'integer',
        'campo_a' => 'string',
        'campo_b' => 'string',
        'volumen_muestra' => 'string',
        'mgl' => 'string',
        'hr' => 'string',
        'centigrados' => 'string',
        'concentracion' => 'float',
        'resultado_promedio' => 'string',
        'consecutivo' => 'string',
        'vigencia' => 'string',
        'consecutivo_ensayo' => 'string',
        'estado' => 'string',
        'tipo' => 'string',
        'user_name' => 'string',
        'id_muestra' => 'string',
        'duplicado' => 'string',
        'metodo' => 'string',
        'id_carta' => 'string',
        'aprobar_resultado' => 'string',
        'aprobacion_usuario' => 'string',
        'observacion_analista' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_solidos_id' => 'required',
        'lc_sample_taking_has_lc_list_trials_id' => 'nullable',
        'users_id' => 'required',
        'campo_a' => 'nullable|string|max:255',
        'campo_b' => 'nullable|string|max:255',
        'volumen_muestra' => 'nullable|string|max:255',
        'mgl' => 'nullable|string|max:255',
        'hr' => 'nullable|string|max:255',
        'centigrados' => 'nullable|string|max:255',
        'concentracion' => 'nullable|string|max:255',
        'resultado_promedio' => 'nullable|string|max:255',
        'consecutivo' => 'nullable|string|max:255',
        'vigencia' => 'nullable|string|max:255',
        'consecutivo_ensayo' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:255',
        'tipo' => 'nullable|string|max:255',
        'user_name' => 'nullable|string|max:255',
        'id_muestra' => 'nullable|string|max:255',
        'duplicado' => 'nullable|string|max:255',
        'metodo' => 'nullable|string|max:255',
        'id_carta' => 'nullable|string|max:255',
        'aprobar_resultado' => 'nullable|string|max:255',
        'aprobacion_usuario' => 'nullable|string|max:255',
        'observacion_analista' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTakingHasLcListTrials() {
        return $this->belongsTo(\Modules\Leca\Models\LcSampleTakingHasLcListTrial::class, 'lc_sample_taking_has_lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSolidos() {
        return $this->belongsTo(\Modules\Leca\Models\LcSolido::class, 'lc_solidos_id');
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
    public function lcDprPrSolidosDis() {
        return $this->hasMany(\Modules\Leca\Models\DprPrSolidosDi::class, 'lc_ensayo_solidos_dis_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcObservacionesDuplicadoSolidosDis() {
        return $this->hasMany(\Modules\Leca\Models\ObservacionesDuplicadoSolidosDis::class, 'lc_ensayo_solidos_dis_id');
    }
}
