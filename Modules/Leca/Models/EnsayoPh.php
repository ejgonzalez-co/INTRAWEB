<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnsayoPh
 * @package Modules\Leca\Models
 * @version August 3, 2022, 2:37 pm -05
 *
 * @property Modules\Leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDprPrPhs
 * @property \Illuminate\Database\Eloquent\Collection $lcObservacionesDuplicadoPhs
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
 * @property string $ph1
 * @property string $ph2
 * @property string $lectura
 * @property string $expresion
 * @property string $temperatura_ph
 */
class EnsayoPh extends Model
{
    use SoftDeletes;

    public $table = 'lc_ensayo_ph';

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
        'ph1',
        'ph2',
        'lectura',
        'expresion',
        'temperatura_ph',
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
        'ph1' => 'string',
        'ph2' => 'string',
        'lectura' => 'string',
        'expresion' => 'string',
        'temperatura_ph' => 'string',
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
        'ph1' => 'nullable|string|max:250',
        'ph2' => 'nullable|string|max:250',
        'lectura' => 'nullable|string|max:250',
        'expresion' => 'nullable|string|max:250',
        'temperatura_ph' => 'nullable|string|max:250',
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
    public function lcDprPrPh()
    {
        return $this->hasMany(\Modules\Leca\Models\DprprPh::class, 'lc_ensayo_ph_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcObservacionesDuplicadoPh()
    {
        return $this->hasMany(\Modules\Leca\Models\ObservacionesDuplicadoPh::class, 'lc_ensayo_ph_id');
    }
}
