<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoSulfatos
 * @package Modules\leca\Models
 * @version December 17, 2022, 10:34 pm -05
 *
 * @property Modules\leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\leca\Models\LcSulfato $lcSulfatos
 * @property Modules\leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprPrSulfatos
 * @property \Illuminate\Database\Eloquent\Collection $lcObservacionesDuplicadoSulfatos
 * @property integer $users_id
 * @property integer $lc_sample_taking_has_lc_list_trials_id
 * @property integer $lc_sulfatos_id
 * @property string $buffer
 * @property string $formula
 * @property string $volumen_muestra
 * @property string $valor_turb_uno
 * @property string $valor_turb_dos
 * @property string $sulfato_resultado_a
 * @property string $sulfato_resultado_b
 * @property string $concentracion
 * @property string $metodo
 * @property string $resultado_promedio
 * @property string $consecutivo
 * @property string $vigencia
 * @property string $consecutivo_ensayo
 * @property string $estado
 * @property string $tipo
 * @property string $user_name
 * @property string $id_muestra
 * @property string $duplicado
 * @property string $id_carta
 * @property string $aprobar_resultado
 * @property string $aprobacion_usuario
 * @property string $observacion_analista
 */
class EnsayoSulfatos extends Model {
    use SoftDeletes;

    public $table = 'lc_ensayo_sulfatos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'lc_sample_taking_has_lc_list_trials_id',
        'lc_sulfatos_id',
        'buffer',
        'formula',
        'volumen_muestra',
        'valor_turb_uno',
        'valor_turb_dos',
        'sulfato_resultado_a',
        'sulfato_resultado_b',
        'concentracion',
        'metodo',
        'resultado_promedio',
        'consecutivo',
        'vigencia',
        'consecutivo_ensayo',
        'estado',
        'tipo',
        'user_name',
        'id_muestra',
        'duplicado',
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
        'users_id' => 'integer',
        // 'lc_sample_taking_has_lc_list_trials_id' => 'integer',
        'lc_sulfatos_id' => 'integer',
        'buffer' => 'string',
        'formula' => 'string',
        'volumen_muestra' => 'string',
        'valor_turb_uno' => 'string',
        'valor_turb_dos' => 'string',
        'sulfato_resultado_a' => 'string',
        'sulfato_resultado_b' => 'string',
        'concentracion' => 'float',
        'metodo' => 'string',
        'resultado_promedio' => 'string',
        'consecutivo' => 'string',
        'vigencia' => 'string',
        'consecutivo_ensayo' => 'string',
        'estado' => 'string',
        'tipo' => 'string',
        'user_name' => 'string',
        'id_muestra' => 'string',
        'duplicado' => 'string',
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
        // 'users_id' => 'required',
        // 'lc_sample_taking_has_lc_list_trials_id' => 'nullable',
        // 'lc_sulfatos_id' => 'required',
        'buffer' => 'nullable|string|max:255',
        'formula' => 'nullable|string|max:255',
        'volumen_muestra' => 'nullable|string|max:255',
        'valor_turb_uno' => 'nullable|string|max:255',
        'valor_turb_dos' => 'nullable|string|max:255',
        'sulfato_resultado_a' => 'nullable|string|max:255',
        'sulfato_resultado_b' => 'nullable|string|max:255',
        'concentracion' => 'nullable|string|max:255',
        'metodo' => 'nullable|string|max:255',
        'resultado_promedio' => 'nullable|string|max:255',
        'consecutivo' => 'nullable|string|max:255',
        'vigencia' => 'nullable|string|max:255',
        'consecutivo_ensayo' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:255',
        'tipo' => 'nullable|string|max:255',
        'user_name' => 'nullable|string|max:255',
        'id_muestra' => 'nullable|string|max:255',
        'duplicado' => 'nullable|string|max:255',
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
        return $this->belongsTo(\Modules\leca\Models\LcSampleTakingHasLcListTrial::class, 'lc_sample_taking_has_lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSulfatos() {
        return $this->belongsTo(\Modules\leca\Models\LcSulfato::class, 'lc_sulfatos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDprPrSulfatos() {
        return $this->hasMany(\Modules\Leca\Models\DprprSulfato::class, 'lc_ensayo_sulfatos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcObservacionesDuplicadoSulfatos() {
        return $this->hasMany(\Modules\leca\Models\LcObservacionesDuplicadoSulfato::class, 'lc_ensayo_sulfatos_id');
    }
}
