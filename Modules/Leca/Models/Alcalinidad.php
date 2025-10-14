<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Alcalinidad
 * @package Modules\Leca\Models
 * @version February 22, 2022, 10:47 am -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_list_trials_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $proceso
 * @property string $documento_referencia
 * @property string $año
 * @property string $mes
 * @property string $limite_cuantificacion
 * @property string $v_muestra
 * @property string $alcalinidad_alta
 * @property string $factor_alcal_alta
 * @property string $alcalinidad_baja
 * @property string $factor_alcal_baja
 * @property string $fehca_curva
 * @property string $curva
 * @property string $alcalinidad_total
 * @property string $obervacion
 * @property string $firma
 * @property string $nombre
 * @property string $cargo
 */
class Alcalinidad extends Model
{
    use SoftDeletes;

    public $table = 'lc_alcalinidad';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_list_trials_id',
        'users_id',
        'user_name',
        'proceso',
        'documento_referencia',
        'año',
        'mes',
        'limite_cuantificacion',
        'v_muestra',
        'alcalinidad_alta',
        'factor_alcal_alta',
        'alcalinidad_baja',
        'factor_alcal_baja',
        'fehca_curva',
        'curva',
        'alcalinidad_total',
        'obervacion',
        'firma',
        'nombre',
        'cargo',
        'user_remplace',
        'user_remplace_admin',
        'observations_edit',
        'decimales',
        'patron_esperado_dos',
        'patron_esperado',
        'valor_esperado_lcm'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_list_trials_id' => 'integer',
        'users_id' => 'integer',
        'user_name' => 'string',
        'proceso' => 'string',
        'documento_referencia' => 'string',
        'año' => 'string',
        'mes' => 'string',
        'limite_cuantificacion' => 'string',
        'v_muestra' => 'string',
        'alcalinidad_alta' => 'string',
        'factor_alcal_alta' => 'string',
        'alcalinidad_baja' => 'string',
        'factor_alcal_baja' => 'string',
        'fehca_curva' => 'string',
        'curva' => 'string',
        'alcalinidad_total' => 'string',
        'obervacion' => 'string',
        'observations_edit' => 'string',
        'firma' => 'string',
        'nombre' => 'string',
        'cargo' => 'string',
        'user_remplace' => 'string',
        'user_remplace_admin' => 'string',
        'decimales' => 'integer',
        'valor_esperado_lcm' => 'float',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_list_trials_id' => 'required',
        'users_id' => 'required',
        'user_name' => 'nullable|string|max:255',
        'proceso' => 'nullable|string|max:255',
        'documento_referencia' => 'nullable|string|max:255',
        'año' => 'nullable|string|max:255',
        'mes' => 'nullable|string|max:255',
        'limite_cuantificacion' => 'nullable|string|max:255',
        'v_muestra' => 'nullable|string|max:255',
        'alcalinidad_alta' => 'nullable|string|max:255',
        'factor_alcal_alta' => 'nullable|string|max:255',
        'alcalinidad_baja' => 'nullable|string|max:255',
        'factor_alcal_baja' => 'nullable|string|max:255',
        'fehca_curva' => 'nullable|string|max:255',
        'curva' => 'nullable|string|max:255',
        'valor_esperado_lcm' => 'nullable|string|max:255',
        'alcalinidad_total' => 'nullable|string|max:255',
        'obervacion' => 'nullable|string',
        'observations_edit' => 'nullable|string|min:30',
        'firma' => 'nullable|string',
        'nombre' => 'nullable|string|max:255',
        'cargo' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcListTrials()
    {
        return $this->belongsTo(\Modules\Leca\Models\LcListTrial::class, 'lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}