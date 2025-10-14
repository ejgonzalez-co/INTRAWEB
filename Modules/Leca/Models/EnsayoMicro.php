<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoMicro
 * @package Modules\leca\Models
 * @version October 19, 2022, 5:28 pm -05
 *
 * @property Modules\leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprPrMicros
 * @property \Illuminate\Database\Eloquent\Collection $lcObservacionesDuplicadoMicros
 * @property integer $users_id
 * @property integer $lc_sample_taking_has_lc_list_trials_id
 * @property integer $consecutivo
 * @property integer $consecutivo_ensayo
 * @property string $estado
 * @property string $tipo
 * @property string $duplicado
 * @property string $metodo
 * @property string $user_name
 * @property string $id_muestra
 * @property string $resultado
 * @property string $resultado_completo
 * @property string $tecnica
 * @property string $quien_realizo_siembra
 * @property string $quien_realizo_lectura
 * @property string $resultado_general
 * @property string $convertir_ufc
 * @property string $resultado_general_ufc
 * @property string $aprobar_resultado
 * @property string $comprobar_control
 * @property string $fecha_siembra
 * @property string $hora_siembra
 * @property string $fecha_incubacion
 * @property string $hora_incubacion
 * @property string $dilucion_utilizada
 * @property string $fecha_lectura
 * @property string $hora_lectura
 * @property string $pozos_grandes
 * @property string $pozos_pequeños
 */
class EnsayoMicro extends Model {
    use SoftDeletes;

    public $table = 'lc_ensayos_microbiologicos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'lc_sample_taking_has_lc_list_trials_id',
        'consecutivo',
        'ensayo',
        'vigencia',
        'consecutivo_ensayo',
        'estado',
        'tipo',
        'duplicado',
        'metodo',
        'user_name',
        'id_muestra',
        'resultado',
        'resultado_completo',
        'tecnica',
        'quien_realizo_siembra',
        'quien_realizo_lectura',
        'resultado_general',
        'convertir_ufc',
        'resultado_general_ufc',
        'aprobar_resultado',
        'aprobacion_usuario',
        'comprobar_control',
        'fecha_siembra',
        'hora_siembra',
        'fecha_incubacion',
        'hora_incubacion',
        'dilucion_utilizada',
        'fecha_lectura',
        'hora_lectura',
        'pozos_grandes',
        'pozos_pequeños',
        'observacion_analista',
        'lc_micro_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'lc_sample_taking_has_lc_list_trials_id' => 'integer',
        'consecutivo' => 'string',
        'consecutivo_ensayo' => 'string',
        'estado' => 'string',
        'tipo' => 'string',
        'duplicado' => 'string',
        'metodo' => 'string',
        'user_name' => 'string',
        'id_muestra' => 'string',
        'resultado' => 'string',
        'resultado_completo' => 'string',
        'tecnica' => 'string',
        'quien_realizo_siembra' => 'string',
        'quien_realizo_lectura' => 'string',
        'resultado_general' => 'float',
        'convertir_ufc' => 'string',
        'resultado_general_ufc' => 'string',
        'aprobar_resultado' => 'string',
        'aprobacion_usuario' => 'string',
        'ensayo' => 'string',
        'vigencia' => 'string',
        'comprobar_control' => 'string',
        'fecha_siembra' => 'string',
        'hora_siembra' => 'string',
        'fecha_incubacion' => 'string',
        'hora_incubacion' => 'string',
        'dilucion_utilizada' => 'string',
        'fecha_lectura' => 'string',
        'hora_lectura' => 'string',
        'pozos_grandes' => 'string',
        'pozos_pequeños' => 'string',
        'lc_micro_id' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'lc_sample_taking_has_lc_list_trials_id' => 'required',
        'consecutivo' => 'nullable|string|max:120',
        'consecutivo_ensayo' => 'nullable|string|max:120',
        'estado' => 'nullable|string|max:250',
        'tipo' => 'nullable|string|max:250',
        'duplicado' => 'nullable|string|max:250',
        'metodo' => 'nullable|string|max:250',
        'user_name' => 'nullable|string|max:250',
        'id_muestra' => 'nullable|string|max:250',
        'resultado' => 'nullable|string|max:250',
        'resultado_completo' => 'nullable|string|max:250',
        'tecnica' => 'nullable|string|max:250',
        'quien_realizo_siembra' => 'nullable|string|max:250',
        'quien_realizo_lectura' => 'nullable|string|max:250',
        'resultado_general' => 'nullable|string|max:250',
        'convertir_ufc' => 'nullable|string|max:250',
        'resultado_general_ufc' => 'nullable|string|max:250',
        'aprobar_resultado' => 'nullable|string|max:250',
        'comprobar_control' => 'nullable|string|max:250',
        'fecha_siembra' => 'nullable|string|max:250',
        'hora_siembra' => 'nullable|string|max:250',
        'fecha_incubacion' => 'nullable|string|max:250',
        'hora_incubacion' => 'nullable|string|max:250',
        'dilucion_utilizada' => 'nullable|string|max:250',
        'fecha_lectura' => 'nullable|string|max:250',
        'hora_lectura' => 'nullable|string|max:250',
        'pozos_grandes' => 'nullable|string|max:250',
        'pozos_pequeños' => 'nullable|string|max:250',
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
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDprPrMicros() {
        return $this->hasMany(\Modules\leca\Models\LcDprPrMicro::class, 'lc_ensayos_microbiologicos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcObservacionesDuplicadoMicros() {
        return $this->hasMany(\Modules\Leca\Models\ObservacionesMicro::class, 'lc_ensayos_microbiologicos_id');
    }
}
