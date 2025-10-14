<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Patron
 * @package Modules\Leca\Models
 * @version March 7, 2022, 9:08 am -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_list_trials_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $parametro
 * @property string $metodo
 * @property string $recuperacion
 * @property string $patron_control
 * @property string $mes
 * @property string $año
 * @property string $n_esta
 * @property string $x_esta
 * @property string $s_esta
 * @property string $n_anterior
 * @property string $x_anterior
 * @property string $s_anterior
 * @property string $lcs
 * @property string $las
 * @property string $lai
 * @property string $lci
 * @property string $x_mas
 * @property string $x_menos
 * @property string $observacion
 * @property string $nombre_elaboro
 * @property string $cargo_elaboro
 * @property string $firma_elaboro
 * @property string $nombre_reviso
 * @property string $cargo_reviso
 * @property string $firma_reviso
 */
class Patron extends Model
{
    use SoftDeletes;

    public $table = 'lc_patron';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_list_trials_id',
        'users_id',
        'user_name',
        'parametro',
        'metodo',
        'recuperacion',
        'patron_control',
        'mes',
        'año',
        'n_esta',
        'x_esta',
        's_esta',
        'n_anterior',
        'x_anterior',
        's_anterior',
        'lcs',
        'las',
        'lai',
        'lci',
        'x_mas',
        'x_menos',
        'observacion',
        'nombre_elaboro',
        'cargo_elaboro',
        'emergencia_patron',
        'firma_elaboro',
        'nombre_reviso',
        'cargo_reviso',
        'firma_reviso',
        'observations_edit',
        'decimales',
        'lmt_error',
        'n_anterior_50',
        'x_anterior_50',
        's_anterior_50',
        'lcs_50',
        'las_50',
        'lai_50',
        'lci_50',
        'x_mas_50',
        'x_menos_50',
        'n_anterior_lcm',
        'x_anterior_lcm',
        's_anterior_lcm',
        'lcs_lcm',
        'las_lcm',
        'lai_lcm',
        'lci_lcm',
        'x_mas_lcm',
        'x_menos_lcm',
        'lcs_porc_lcm',
        'las_porc_lcm',
        'lai_porc_lcm',
        'lci_porc_lcm',
        'x_mas_porc_lcm',
        'x_menos_porc_lcm',
        'n_ant_porc_std',
        'x_ant_porc_std',
        's_ant_porc_std',
        'lcs_porc_std',
        'las_porc_std',
        'lai_porc_std',
        'lci_porc_std',
        'x_mas_porc_std',
        'x_menos_porc_std',
        'n_ant_dpr',
        'x_ant_dpr',
        's_ant_dpr',
        'n_ant_adicionado',
        'x_ant_adicionado',
        's_ant_adicionado',
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
        'parametro' => 'string',
        'metodo' => 'string',
        'recuperacion' => 'string',
        'patron_control' => 'string',
        'emergencia_patron' => 'string',
        'mes' => 'string',
        'año' => 'string',
        'n_esta' => 'float',
        'x_esta' => 'float',
        's_esta' => 'float',
        'n_anterior' => 'float',
        'x_anterior' => 'float',
        's_anterior' => 'float',
        'n_anterior_50' => 'float',
        'x_anterior_50' => 'float',
        's_anterior_50' => 'float',
        'lcs_50' => 'float',
        'las_50' => 'float',
        'lai_50' => 'float',
        'lci_50' => 'float',
        'x_mas_50' => 'float',
        'x_menos_50' => 'float',
        'n_anterior_lcm' => 'float',
        'x_anterior_lcm' => 'float',
        's_anterior_lcm' => 'float',
        'lcs' => 'float',
        'las' => 'float',
        'lai' => 'float',
        'lci' => 'float',
        'x_mas' => 'float',
        'x_menos' => 'float',
        'observacion' => 'string',
        'observations_edit' => 'string',
        'nombre_elaboro' => 'string',
        'cargo_elaboro' => 'string',
        'firma_elaboro' => 'string',
        'nombre_reviso' => 'string',
        'cargo_reviso' => 'string',
        'firma_reviso' => 'string',
        'decimales' => 'string',
        'lcs_porc_lcm' => 'float',
        'las_porc_lcm' => 'float',
        'lai_porc_lcm' => 'float',
        'lci_porc_lcm' => 'float',
        'x_mas_porc_lcm' => 'float',
        'x_menos_porc_lcm' => 'float',
        'n_ant_porc_std' => 'float',
        'x_ant_porc_std' => 'float',
        's_ant_porc_std' => 'float',
        'lcs_porc_std' => 'float',
        'las_porc_std' => 'float',
        'lai_porc_std' => 'float',
        'lci_porc_std' => 'float',
        'x_mas_porc_std' => 'float',
        'x_menos_porc_std' => 'float',
        'n_ant_dpr' => 'float',
        'x_ant_dpr' => 'float',
        's_ant_dpr' => 'float',
        'n_ant_adicionado' => 'float',
        'x_ant_adicionado' => 'float',
        's_ant_adicionado' => 'float',
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
        'parametro' => 'nullable|string|max:255',
        'metodo' => 'nullable|string|max:255',
        'recuperacion' => 'nullable|string|max:255',
        'patron_control' => 'nullable|string|max:255',
        'mes' => 'nullable|string|max:45',
        'año' => 'nullable|string|max:45',
        'n_esta' => 'nullable|string|max:255',
        'x_esta' => 'nullable|string|max:255',
        's_esta' => 'nullable|string|max:255',
        'n_anterior' => 'nullable|string|max:255',
        'x_anterior' => 'nullable|string|max:255',
        's_anterior' => 'nullable|string|max:255',
        'lcs' => 'nullable|string|max:255',
        'las' => 'nullable|string|max:255',
        'lai' => 'nullable|string|max:255',
        'lci' => 'nullable|string|max:255',
        'x_mas' => 'nullable|string|max:255',
        'x_menos' => 'nullable|string|max:255',
        'observacion' => 'nullable|string|max:255',
        'observations_edit' => 'nullable|string|min:30',
        'nombre_elaboro' => 'nullable|string|max:255',
        'cargo_elaboro' => 'nullable|string|max:255',
        'firma_elaboro' => 'nullable|string|max:255',
        'nombre_reviso' => 'nullable|string|max:255',
        'cargo_reviso' => 'nullable|string|max:255',
        'firma_reviso' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'decimales' => 'nullable|string|max:255',
        'lcs_porc_lcm' => 'nullable|string|max:255',
        'las_porc_lcm' => 'nullable|string|max:255',
        'lai_porc_lcm' => 'nullable|string|max:255',
        'lci_porc_lcm' => 'nullable|string|max:255',
        'x_mas_porc_lcm' => 'nullable|string|max:255',
        'x_menos_porc_lcm' => 'nullable|string|max:255',
        'n_ant_porc_std' => 'nullable|string|max:255',
        'x_ant_porc_std' => 'nullable|string|max:255',
        's_ant_porc_std' => 'nullable|string|max:255',
        'lcs_porc_std' => 'nullable|string|max:255',
        'las_porc_std' => 'nullable|string|max:255',
        'lai_porc_std' => 'nullable|string|max:255',
        'lci_porc_std' => 'nullable|string|max:255',
        'x_mas_porc_std' => 'nullable|string|max:255',
        'x_menos_porc_std' => 'nullable|string|max:255',
        'n_ant_dpr' => 'nullable|string|max:255',
        'x_ant_dpr' => 'nullable|string|max:255',
        's_ant_dpr' => 'nullable|string|max:255',
        'n_ant_adicionado' => 'nullable|string|max:255',
        'x_ant_adicionado' => 'nullable|string|max:255',
        'lcs_50' => 'nullable|string|max:255',
        'las_50' => 'nullable|string|max:255',
        'lai_50' => 'nullable|string|max:255',
        'lci_50' => 'nullable|string|max:255',
        'x_mas_50' => 'nullable|string|max:255',
        'x_menos_50' => 'nullable|string|max:255',
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