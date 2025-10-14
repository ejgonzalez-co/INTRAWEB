<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoAlcalinidad
 * @package Modules\Leca\Models
 * @version July 15, 2022, 3:05 pm -05
 *
 * @property Modules\Leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprPrAlcalinidads
 * @property \Illuminate\Database\Eloquent\Collection $lcDprPrObservacionesDuplicadoAlcalinidads
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
 * @property string $volumen_muestra
 * @property string $normalidad_solucion
 * @property string $alcalinidad_select
 * @property string $volumen_f
 * @property string $volumen_m
 * @property string $volumen_total_h2
 * @property string $volumen_ph
 */
class EnsayoAlcalinidad extends Model
{
    use SoftDeletes;

    public $table = 'lc_ensayo_alcalinidad';

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
        'volumen_muestra',
        'patron_pendiente',
        'normalidad_solucion',
        'alcalinidad_select',
        'volumen_f',
        'volumen_m',
        'volumen_total_h2',
        'volumen_ph',
        'ph',
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
        'volumen_muestra' => 'string',
        'patron_pendiente' => 'string',
        'normalidad_solucion' => 'string',
        'alcalinidad_total' => 'string',
        'alcalinidad_select' => 'string',
        'volumen_f' => 'string',
        'volumen_m' => 'string',
        'volumen_total_h2' => 'string',
        'volumen_ph' => 'string',
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
        'user_name' => 'nullable|string|max:250',
        'id_muestra' => 'nullable|string|max:250',
        'resultado' => 'nullable|string|max:250',
        'volumen_muestra' => 'nullable|string|max:250',
        'patron_pendiente' => 'nullable|string|max:250',
        'normalidad_solucion' => 'nullable|string|max:250',
        'alcalinidad_total' => 'nullable|string|max:250',
        'alcalinidad_select' => 'nullable|string|max:250',
        'volumen_f' => 'nullable|string|max:250',
        'volumen_m' => 'nullable|string|max:250',
        'volumen_total_h2' => 'nullable|string|max:250',
        'volumen_ph' => 'nullable|string|max:250',
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
    public function DprPrAlcalinidad()
    {
        return $this->hasMany(\Modules\Leca\Models\DprprAlcalinidad::class, 'lc_ensayo_alcalinidad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDprPrObservacionesDuplicadoAlcalinidads()
    {
        return $this->hasMany(\Modules\Leca\Models\ObsercacionesDuplicadoAlcalinidad::class, 'lc_ensayo_alcalinidad_id');
    }
}