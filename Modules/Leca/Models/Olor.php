<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Color
 * @package Modules\Leca\Models
 * @version March 9, 2022, 9:42 am -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_list_trials_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $proceso
 * @property string $año
 * @property string $mes
 * @property string $fecha_ajuste_curva_uno
 * @property string $fecha_ajuste_curva_dos
 * @property string $fecha_ajuste_curva_tres
 * @property string $documento_referencia_uno
 * @property string $documento_referencia_dos
 * @property string $documento_referencia_tres
 * @property string $limite_cuantificacion_uno
 * @property string $limite_cuantificacion_dos
 * @property string $limite_cuantificacion_tres
 * @property string $sustancias_flotantes
 * @property string $nombre_patron_uno
 * @property string $valor_esperador_uno
 * @property string $nombre_patron_dos
 * @property string $valor_esperado_dos
 * @property string $nombre_patron_tres
 * @property string $valor_esperador_tres
 * @property string $obervacion
 * @property string $firma
 * @property string $nombre
 * @property string $cargo
 * @property string $user_remplace
 * @property string $user_remplace_admin
 */
class Olor extends Model
{
    use SoftDeletes;

    public $table = 'lc_olor';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_list_trials_id',
        'users_id',
        'user_name',
        'proceso',
        'año',
        'mes',
        'fecha_ajuste_curva_uno',
        'fecha_ajuste_curva_dos',
        'fecha_ajuste_curva_tres',
        'documento_referencia_uno',
        'documento_referencia_dos',
        'documento_referencia_tres',
        'limite_cuantificacion_uno',
        'limite_cuantificacion_dos',
        'limite_cuantificacion_tres',
        'sustancias_flotantes',
        'nombre_patron_uno',
        'valor_esperador_uno',
        'nombre_patron_dos',
        'valor_esperado_dos',
        'nombre_patron_tres',
        'valor_esperador_tres',
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
        'año' => 'string',
        'mes' => 'string',
        'fecha_ajuste_curva_uno' => 'string',
        'fecha_ajuste_curva_dos' => 'string',
        'fecha_ajuste_curva_tres' => 'string',
        'documento_referencia_uno' => 'string',
        'documento_referencia_dos' => 'string',
        'documento_referencia_tres' => 'string',
        'limite_cuantificacion_uno' => 'string',
        'limite_cuantificacion_dos' => 'string',
        'limite_cuantificacion_tres' => 'string',
        'sustancias_flotantes' => 'string',
        'nombre_patron_uno' => 'string',
        'valor_esperador_uno' => 'string',
        'nombre_patron_dos' => 'string',
        'valor_esperado_dos' => 'string',
        'nombre_patron_tres' => 'string',
        'valor_esperador_tres' => 'string',
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
        'año' => 'nullable|string|max:255',
        'mes' => 'nullable|string|max:255',
        'fecha_ajuste_curva_uno' => 'nullable|string|max:255',
        'fecha_ajuste_curva_dos' => 'nullable|string|max:255',
        'fecha_ajuste_curva_tres' => 'nullable|string|max:255',
        'documento_referencia_uno' => 'nullable|string|max:255',
        'documento_referencia_dos' => 'nullable|string|max:255',
        'documento_referencia_tres' => 'nullable|string|max:255',
        'limite_cuantificacion_uno' => 'nullable|string|max:255',
        'limite_cuantificacion_dos' => 'nullable|string|max:255',
        'limite_cuantificacion_tres' => 'nullable|string|max:255',
        'sustancias_flotantes' => 'nullable|string|max:255',
        'nombre_patron_uno' => 'nullable|string|max:255',
        'valor_esperador_uno' => 'nullable|string|max:255',
        'valor_esperado_lcm' => 'nullable|string|max:255',
        'nombre_patron_dos' => 'nullable|string|max:255',
        'valor_esperado_dos' => 'nullable|string|max:255',
        'nombre_patron_tres' => 'nullable|string|max:255',
        'valor_esperador_tres' => 'nullable|string|max:255',
        'obervacion' => 'nullable|string',
        'observations_edit' => 'nullable|string|min:30',
        'firma' => 'nullable|string',
        'nombre' => 'nullable|string|max:255',
        'cargo' => 'nullable|string|max:255',
        'user_remplace' => 'nullable|string|max:255',
        'user_remplace_admin' => 'nullable|string|max:255',
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