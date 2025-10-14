<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoCloruro
 * @package Modules\Leca\Models
 * @version July 29, 2022, 10:43 am -05
 *
 * @property Modules\Leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprPrCloruros
 * @property \Illuminate\Database\Eloquent\Collection $lcObservacionesDuplicadoCloruros
 * @property integer $users_id
 * @property integer $lc_sample_taking_has_lc_list_trials_id
 * @property string $consecutivo
 * @property string $consecutivo_ensayo
 * @property string $estado
 * @property string $tipo
 * @property string $duplicado
 * @property string $metodo
 * @property string $user_name
 * @property string $id_muestra
 * @property string $resultado
 * @property string $resultado_completo
 * @property string $volumen_muestra
 * @property string $volumen_gastado
 * @property string $concentracion
 * @property string $cons_concentracion
 * @property string $ultimo_blanco
 */
class EnsayoCloruro extends Model
{
    use SoftDeletes;

    public $table = 'lc_ensayo_cloruro';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'users_id',
        'lc_sample_taking_has_lc_list_trials_id',
        'consecutivo',
        'consecutivo_ensayo',
        'estado',
        'tipo',
        'duplicado',
        'metodo',
        'user_name',
        'id_muestra',
        'resultado',
        'resultado_completo',
        'volumen_muestra',
        'volumen_gastado',
        'concentracion',
        'cons_concentracion',
        'ultimo_blanco',
        'ph',
        'temperatura',
        'vigencia',
        'pr_inicio',
        'aprobacion_usuario',
        'observacion_analista',
        'tipo_patron',
        'id_carta',
        'cant_concentracion',
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
        'resultado' => 'float',
        'resultado_completo' => 'string',
        'volumen_muestra' => 'string',
        'volumen_gastado' => 'string',
        'concentracion' => 'float',
        'cons_concentracion' => 'string',
        'ultimo_blanco' => 'string',
        'temperatura' => 'string',
        'ph' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'lc_sample_taking_has_lc_list_trials_id' => 'required',
        'consecutivo' => 'nullable|string|max:250',
        'consecutivo_ensayo' => 'nullable|string|max:250',
        'estado' => 'nullable|string|max:250',
        'tipo' => 'nullable|string|max:250',
        'duplicado' => 'nullable|string|max:250',
        'metodo' => 'nullable|string|max:250',
        'temperatura' => 'nullable|string|max:250',
        'user_name' => 'nullable|string|max:250',
        'id_muestra' => 'nullable|string|max:250',
        'resultado' => 'nullable|string|max:250',
        'resultado_completo' => 'nullable|string|max:250',
        'volumen_muestra' => 'nullable|string|max:250',
        'volumen_gastado' => 'nullable|string|max:250',
        'concentracion' => 'nullable|string|max:250',
        'cons_concentracion' => 'nullable|string|max:250',
        'ultimo_blanco' => 'nullable|string|max:250',
        'ph' => 'nullable|string|max:250',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTakingHasLcListTrials()
    {
        return $this->belongsTo(\Modules\Leca\Models\SampleTakingHasLcListTrial::class, 'lc_sample_taking_has_lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDprPrCloruros()
    {
        return $this->hasMany(\Modules\Leca\Models\DprprCloruro::class, 'lc_ensayo_cloruro_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcObservacionesDuplicadoCloruros()
    {
        return $this->hasMany(\Modules\Leca\Models\ObservacionesDuplicadoCloruro::class, 'lc_ensayo_cloruro_id');
    }
}