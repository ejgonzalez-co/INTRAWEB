<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoEspectro
 * @package Modules\leca\Models
 * @version August 29, 2022, 4:05 pm -05
 *
 * @property Modules\leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprPrEspectros
 * @property \Illuminate\Database\Eloquent\Collection $lcObservacionesDuplicadoEspectros
 * @property integer $lc_sample_taking_has_lc_list_trials_id
 * @property integer $users_id
 * @property string $ensayo
 * @property string $consecutivo
 * @property string $consecutivo_ensayo
 * @property string $estado
 * @property string $tipo
 * @property string $duplicado
 * @property string $metodo
 * @property string $regla_decision
 * @property string $user_name
 * @property string $volumen
 * @property string $resultado
 * @property string $curva
 * @property string $pendiente
 * @property string $intercepto
 * @property string $factor_disolucion
 * @property string $absorbancia
 * @property string $concentracion
 * @property string $dpr_add1
 * @property string $dpr_add2
 * @property string $id_muestra
 */
class EnsayoEspectro extends Model
{
    use SoftDeletes;

    public $table = 'lc_ensayo_espectro';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_sample_taking_has_lc_list_trials_id',
        'users_id',
        'ensayo',
        'consecutivo',
        'consecutivo_ensayo',
        'estado',
        'tipo',
        'duplicado',
        'metodo',
        'regla_decision',
        'user_name',
        'volumen',
        'resultado',
        'curva',
        'pendiente',
        'intercepto',
        'factor_disolucion',
        'absorbancia',
        'concentracion',
        'dpr_add1',
        'dpr_add2',
        'id_muestra',
        'vigencia',
        'pr_inicio',
        'aprobacion_usuario',
        'observacion_analista',
        'tipo_patron',
        'id_carta',
        'cant_concentracion',
        'lc_espectro_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_sample_taking_has_lc_list_trials_id' => 'integer',
        'users_id' => 'integer',
        'ensayo' => 'string',
        'consecutivo' => 'string',
        'consecutivo_ensayo' => 'string',
        'estado' => 'string',
        'tipo' => 'string',
        'duplicado' => 'string',
        'metodo' => 'string',
        'regla_decision' => 'string',
        'user_name' => 'string',
        'volumen' => 'string',
        'resultado' => 'float',
        'curva' => 'string',
        'pendiente' => 'string',
        'intercepto' => 'string',
        'factor_disolucion' => 'string',
        'absorbancia' => 'string',
        'concentracion' => 'float',
        'dpr_add1' => 'string',
        'dpr_add2' => 'string',
        'id_muestra' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_sample_taking_has_lc_list_trials_id' => 'nullable',
        'users_id' => 'required',
        'ensayo' => 'nullable|string|max:120',
        'consecutivo' => 'nullable|string|max:120',
        'consecutivo_ensayo' => 'nullable|string|max:120',
        'estado' => 'nullable|string|max:255',
        'tipo' => 'nullable|string|max:255',
        'duplicado' => 'nullable|string|max:120',
        'metodo' => 'nullable|string|max:120',
        'regla_decision' => 'nullable|string|max:120',
        'user_name' => 'nullable|string|max:255',
        'volumen' => 'nullable|string|max:120',
        'resultado' => 'nullable|string|max:120',
        'curva' => 'nullable|string|max:120',
        'pendiente' => 'nullable|string|max:120',
        'intercepto' => 'nullable|string|max:120',
        'factor_disolucion' => 'nullable|string|max:120',
        'absorbancia' => 'nullable|string|max:120',
        'concentracion' => 'nullable|string|max:120',
        'dpr_add1' => 'nullable|string|max:120',
        'dpr_add2' => 'nullable|string|max:120',
        'id_muestra' => 'nullable|string|max:120',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTakingHasLcListTrials()
    {
        return $this->belongsTo(\Modules\leca\Models\LcSampleTakingHasLcListTrial::class, 'lc_sample_taking_has_lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDprPrEspectros()
    {
        return $this->hasMany(\Modules\leca\Models\DprPrEspectro::class, 'lc_ensayo_espectro_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ObservacionesDuplicadoEspectros()
    {
        return $this->hasMany(\Modules\leca\Models\ObservacionesEspectro::class, 'lc_ensayo_espectro_id');
    }
}