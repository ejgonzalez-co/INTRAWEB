<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DurezaTotal
 * @package Modules\Leca\Models
 * @version February 21, 2022, 10:13 am -05
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
 * @property string $concentracion
 * @property string $factor_calculo
 * @property string $nombre_patron
 * @property string $valor_esperado
 * @property string $dureza_total
 * @property string $obervacion
 * @property string $firma
 * @property string $nombre
 * @property string $cargo
 */
class DurezaTotal extends Model {
    use SoftDeletes;

    public $table = 'lc_dureza_total';
    
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
        'concentracion',
        'factor_calculo',
        'nombre_patron',
        'valor_esperado',
        'dureza_total',
        'obervacion',
        'firma',
        'nombre',
        'cargo',
        'user_remplace',
        'user_remplace_admin',
        'observations_edit',
        'decimales',
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
        'concentracion' => 'string',
        'factor_calculo' => 'string',
        'nombre_patron' => 'string',
        'valor_esperado' => 'float',
        'dureza_total' => 'string',
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
        'concentracion' => 'nullable|string|max:255',
        'factor_calculo' => 'nullable|string|max:255',
        'nombre_patron' => 'nullable|string|max:255',
        'valor_esperado' => 'nullable|string|max:255',
        'valor_esperado_lcm' => 'nullable|string|max:255',
        'dureza_total' => 'nullable|string|max:255',
        'obervacion' => 'nullable|string',
        'observations_edit' => 'nullable|string|min:30',
        'firma' => 'nullable|string',
        'nombre' => 'nullable|string|max:255',
        'cargo' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcListTrials() {
        return $this->belongsTo(\Modules\Leca\Models\LcListTrial::class, 'lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
