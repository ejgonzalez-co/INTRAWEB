<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoColor
 * @package Modules\Leca\Models
 * @version August 3, 2022, 5:33 pm -05
 *
 * @property Modules\Leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprprTurbidezSustanciass
 * @property \Illuminate\Database\Eloquent\Collection $lcObservacionesDuplicadoSustanciass
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
 * @property string $sustanciasFlotantes
 * @property string $temperatura_con
 * @property string $olor
 * @property string $olor
 * @property string $temperatura_col
 */
class EnsayoSustanciasFlotantes extends Model
{
    use SoftDeletes;

    public $table = 'lc_ensayo_sustancias_flotantes';

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
        'sustanciasFlotantes',
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
        'resultado' => 'string',
        'resultado_completo' => 'string',
        'sustanciasFlotantes' => 'string',
        'temperatura_con' => 'string',
        'olor' => 'string',
        'olor' => 'string',
        'temperatura_col' => 'string',
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
        'resultado_completo' => 'nullable|string|max:250',
        'sustanciasFlotantes' => 'nullable|string|max:250',
        'temperatura_con' => 'nullable|string|max:250',
        'olor' => 'nullable|string|max:250',
        'olor' => 'nullable|string|max:250',
        'temperatura_col' => 'nullable|string|max:250',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDprPrSustancias()
    {
        return $this->hasMany(\Modules\Leca\Models\DprprSustancias::class, 'lc_ensayo_sustancias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcObservacionesDuplicadoSustancias()
    {
        return $this->hasMany(\Modules\Leca\Models\ObservacionesDuplicadoSustancias::class, 'lc_ensayo_sustancias_id');
    }
}