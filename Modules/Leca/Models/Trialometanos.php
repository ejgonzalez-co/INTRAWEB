<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Trialometanos
 * @package Modules\leca\Models
 * @version August 29, 2022, 2:44 pm -05
 *
 * @property Modules\leca\Models\LcListTrial $lcListTrials
 * @property Modules\leca\Models\User $users
 * @property integer $lc_list_trials_id
 * @property integer $users_id
 * @property integer $decimales
 * @property string $user_name
 * @property string $ensayo
 * @property string $processo
 * @property string $documento_referencia
 * @property string $año
 * @property string $mes
 * @property string $k_pendiente
 * @property string $b_intercepto
 * @property string $fd_factor_dilucion
 * @property string $nombre_patron
 * @property string $valor_esperado
 * @property string $recuperacion_adicionado
 * @property string $limite_cuantificacion
 * @property string $curva_numero
 * @property string $fecha_curva
 * @property string $ensayo_mgl_no3
 * @property string $obervacion
 * @property string $firma
 * @property string $nombre
 * @property string $cargo
 * @property string $user_remplace
 * @property string $user_remplace_admin
 * @property string $observations_edit
 */
class Trialometanos extends Model {
    use SoftDeletes;

    public $table = 'lc_trialometanos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_list_trials_id',
        'users_id',
        'decimales',
        'user_name',
        'ensayo',
        'processo',
        'documento_referencia',
        'año',
        'mes',
        'k_pendiente',
        'b_intercepto',
        'fd_factor_dilucion',
        'nombre_patron',
        'valor_esperado',
        'recuperacion_adicionado',
        'limite_cuantificacion',
        'curva_numero',
        'fecha_curva',
        'ensayo_mgl_no3',
        'obervacion',
        'firma',
        'nombre',
        'cargo',
        'user_remplace',
        'user_remplace_admin',
        'observations_edit',
        'valor_esperado_adc',
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
        'decimales' => 'integer',
        'user_name' => 'string',
        'ensayo' => 'string',
        'processo' => 'string',
        'documento_referencia' => 'string',
        'año' => 'string',
        'mes' => 'string',
        'k_pendiente' => 'string',
        'b_intercepto' => 'string',
        'fd_factor_dilucion' => 'string',
        'nombre_patron' => 'string',
        'valor_esperado' => 'float',
        'recuperacion_adicionado' => 'string',
        'limite_cuantificacion' => 'string',
        'curva_numero' => 'string',
        'fecha_curva' => 'string',
        'ensayo_mgl_no3' => 'string',
        'obervacion' => 'string',
        'firma' => 'string',
        'nombre' => 'string',
        'cargo' => 'string',
        'user_remplace' => 'string',
        'user_remplace_admin' => 'string',
        'observations_edit' => 'string',
        'valor_esperado_adc' => 'float',
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
        'decimales' => 'nullable|integer',
        'user_name' => 'nullable|string|max:255',
        'ensayo' => 'nullable|string|max:255',
        'processo' => 'nullable|string|max:255',
        'documento_referencia' => 'nullable|string|max:255',
        'año' => 'nullable|string|max:45',
        'mes' => 'nullable|string|max:45',
        'k_pendiente' => 'nullable|string|max:255',
        'b_intercepto' => 'nullable|string|max:255',
        'fd_factor_dilucion' => 'nullable|string|max:255',
        'nombre_patron' => 'nullable|string|max:255',
        'valor_esperado' => 'nullable|string|max:255',
        'valor_esperado_adc' => 'nullable|string|max:255',
        'valor_esperado_lcm' => 'nullable|string|max:255',
        'recuperacion_adicionado' => 'nullable|string|max:255',
        'limite_cuantificacion' => 'nullable|string|max:255',
        'curva_numero' => 'nullable|string|max:255',
        'fecha_curva' => 'nullable|string|max:255',
        'ensayo_mgl_no3' => 'nullable|string|max:255',
        'obervacion' => 'nullable|string',
        'firma' => 'nullable|string',
        'nombre' => 'nullable|string|max:255',
        'cargo' => 'nullable|string|max:255',
        'user_remplace' => 'nullable|string|max:255',
        'user_remplace_admin' => 'nullable|string|max:255',
        'observations_edit' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcListTrials() {
        return $this->belongsTo(\Modules\leca\Models\ListTrial::class, 'lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }
}
