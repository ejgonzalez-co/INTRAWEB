<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoAluminio
 * @package Modules\Leca\Models
 * @version May 10, 2022, 4:37 pm -05
 *
 * @property Modules\Leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_sample_taking_has_lc_list_trials_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $consecutivo
 * @property string $volumen
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
class EnsayoAluminio extends Model
{
    use SoftDeletes;

    public $table = 'lc_ensayo_aluminio';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_sample_taking_has_lc_list_trials_id',
        'users_id',
        'user_name',
        'consecutivo',
        'metodo',
        'medida_concentracion',
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
        'tipo',
        'estado',
        'consecutivo_ensayo',
        'lc_aluminio_id',
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
        'lc_sample_taking_has_lc_list_trials_id' => 'integer',
        'users_id' => 'integer',
        'lc_aluminio_id' => 'integer',
        'user_name' => 'string',
        'metodo' => 'string',
        'resultado' => 'float',
        'consecutivo' => 'string',
        'volumen' => 'string',
        'curva' => 'string',
        'pendiente' => 'string',
        'intercepto' => 'string',
        'factor_disolucion' => 'string',
        'vigencia' => 'integer',
        'pr_inicio' => 'float',
        'absorbancia' => 'string',
        'concentracion' => 'float',
        'dpr_add1' => 'string',
        'dpr_add2' => 'string',
        'id_muestra' => 'string',
        'tipo' => 'string',
        'estado' => 'string',
        'consecutivo_ensayo' => 'string',
        'aprobacion_usuario' => 'string',
        'medida_concentracion' => 'string',
        'tipo_patron' => 'string',
        'id_carta' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'lc_sample_taking_has_lc_list_trials_id' => 'required',
        // 'users_id' => 'required',
        'user_name' => 'nullable|string|max:255',
        'consecutivo' => 'nullable|string|max:120',
        'volumen' => 'nullable|string|max:120',
        'curva' => 'nullable|string|max:120',
        'pendiente' => 'nullable|string|max:120',
        'intercepto' => 'nullable|string|max:120',
        'factor_disolucion' => 'nullable|string|max:120',
        'tipo' => 'nullable|string|max:120',
        'estado' => 'nullable|string|max:120',
        'absorbancia' => 'nullable|string|max:120',
        'concentracion' => 'nullable|string|max:120',
        'dpr_add1' => 'nullable|string|max:120',
        'dpr_add2' => 'nullable|string|max:120',
        'id_muestra' => 'nullable|string|max:120',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'consecutivo_ensayo' => 'nullable',
        'tipo_patron' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTakingHasLcListTrials()
    {
        return $this->belongsTo(\Modules\Leca\Models\LcSampleTakingHasLcListTrial::class, 'lc_sample_taking_has_lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function observaciones()
    {
        return $this->hasMany(\Modules\Leca\Models\ObservacionDuplicado::class, 'lc_ensayo_aluminio_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dprPr()
    {
        return $this->hasMany(\Modules\Leca\Models\Dprpr::class, 'lc_ensayo_aluminio_id');
    }

}