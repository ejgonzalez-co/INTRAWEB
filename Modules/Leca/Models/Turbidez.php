<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ph
 * @package Modules\Leca\Models
 * @version February 22, 2022, 4:34 pm -05
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
 * @property string $limite_cuantificacion_uno
 * @property string $limite_cuantificacion_dos
 * @property string $nombre_patron_uno
 * @property string $valor_esperador_uno
 * @property string $nombre_patron_dos
 * @property string $valor_esperado_dos
 * @property string $obervacion
 * @property string $turbiedad
 * @property string $fecha_curva
 * @property string $pendiente
 * @property string $firma
 * @property string $nombre
 * @property string $cargo
 */
class Turbidez extends Model
{
    use SoftDeletes;

    public $table = 'lc_turbidez';

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
        'limite_cuantificacion_uno',
        'limite_cuantificacion_dos',
        'nombre_patron_uno',
        'valor_esperador_uno',
        'nombre_patron_dos',
        'valor_esperado_dos',
        'obervacion',
        'turbiedad',
        'fecha_curva',
        'pendiente',
        'firma',
        'nombre',
        'cargo',
        'user_remplace',
        'user_remplace_admin',
        'observations_edit',
        'decimales',
        'rango_t_ntu_uno',
        'rango_t_ntu_dos',
        'rango_t_ntu_tres',
        'rango_t_ntu_cuatro',
        'rango_t_ntu_cinco',
        'rango_t_ntu_seis',
        'rango_t_ntu_siete',
        'valor_mas_cer_uno',
        'valor_mas_cer_dos',
        'valor_mas_cer_tres',
        'valor_mas_cer_cuatro',
        'valor_mas_cer_cinco',
        'valor_mas_cer_seis',
        'valor_mas_cer_siete',
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
        'limite_cuantificacion_uno' => 'string',
        'limite_cuantificacion_dos' => 'string',
        'nombre_patron_uno' => 'string',
        'valor_esperador_uno' => 'string',
        'nombre_patron_dos' => 'string',
        'valor_esperado_dos' => 'string',
        'obervacion' => 'string',
        'observations_edit' => 'string',
        'turbiedad' => 'string',
        'fecha_curva' => 'string',
        'pendiente' => 'string',
        'firma' => 'string',
        'nombre' => 'string',
        'cargo' => 'string',
        'user_remplace' => 'string',
        'user_remplace_admin' => 'string',
        'decimales' => 'integer',
        'rango_t_ntu_uno' => 'string',
        'rango_t_ntu_dos' => 'string',
        'rango_t_ntu_tres' => 'string',
        'rango_t_ntu_cuatro' => 'string',
        'rango_t_ntu_cinco' => 'string',
        'rango_t_ntu_seis' => 'string',
        'rango_t_ntu_siete' => 'string',
        'valor_mas_cer_uno' => 'string',
        'valor_mas_cer_dos' => 'string',
        'valor_mas_cer_tres' => 'string',
        'valor_mas_cer_cuatro' => 'string',
        'valor_mas_cer_cinco' => 'string',
        'valor_mas_cer_seis' => 'string',
        'valor_mas_cer_siete' => 'string',
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
        'limite_cuantificacion_uno' => 'nullable|string|max:255',
        'limite_cuantificacion_dos' => 'nullable|string|max:255',
        'nombre_patron_uno' => 'nullable|string|max:255',
        'valor_esperador_uno' => 'nullable|string|max:255',
        'nombre_patron_dos' => 'nullable|string|max:255',
        'valor_esperado_dos' => 'nullable|string|max:255',
        'obervacion' => 'nullable|string',
        'observations_edit' => 'nullable|string|min:30',
        'turbiedad' => 'nullable|string|max:255',
        'fecha_curva' => 'nullable|string|max:255',
        'pendiente' => 'nullable|string|max:255',
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