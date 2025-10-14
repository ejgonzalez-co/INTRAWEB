<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Blancos
 * @package Modules\Leca\Models
 * @version March 3, 2022, 3:27 pm -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_list_trials_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $parametro
 * @property string $tecnica
 * @property string $metodo
 * @property string $frecuencia
 * @property string $año
 * @property string $mes
 * @property string $tipo_grafico
 * @property string $ldm
 * @property string $lcm
 * @property string $observacion
 * @property string $nombre_elaboro
 * @property string $cargo_elaboro
 * @property string $firma_elaboro
 * @property string $nombre_reviso
 * @property string $cargo_reviso
 * @property string $firma_reviso
 */
class Blancos extends Model
{
    use SoftDeletes;

    public $table = 'lc_blancos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_list_trials_id',
        'users_id',
        'user_name',
        'parametro',
        'tecnica',
        'metodo',
        'frecuencia',
        'año',
        'mes',
        'n_anterior',
        'x_anterior',
        's_anterior',
        'tipo_grafico',
        'ldm',
        'lcm',
        'observacion',
        'nombre_elaboro',
        'cargo_elaboro',
        'firma_elaboro',
        'nombre_reviso',
        'cargo_reviso',
        'firma_reviso',
        'observations_edit',
        'decimales',

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
        'tecnica' => 'string',
        'metodo' => 'string',
        'frecuencia' => 'string',
        'año' => 'string',
        'mes' => 'string',
        'tipo_grafico' => 'string',
        'n_anterior' => 'float',
        'x_anterior' => 'float',
        's_anterior' => 'float',
        'ldm' => 'float',
        'lcm' => 'float',
        'observacion' => 'string',
        'observations_edit' => 'string',
        'nombre_elaboro' => 'string',
        'cargo_elaboro' => 'string',
        'firma_elaboro' => 'string',
        'nombre_reviso' => 'string',
        'cargo_reviso' => 'string',
        'firma_reviso' => 'string',
        'decimales' => 'integer',

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
        'tecnica' => 'nullable|string|max:255',
        'metodo' => 'nullable|string|max:255',
        'frecuencia' => 'nullable|string|max:255',
        'año' => 'nullable|string|max:45',
        'mes' => 'nullable|string|max:45',
        'n_anterior' => 'nullable|string|max:255',
        'x_anterior' => 'nullable|string|max:255',
        's_anterior' => 'nullable|string|max:255',
        'tipo_grafico' => 'nullable|string|max:255',
        'ldm' => 'nullable|string|max:255',
        'lcm' => 'nullable|string|max:255',
        'observacion' => 'nullable|string|max:255',
        'observations_edit' => 'nullable|string|min:30',
        'nombre_elaboro' => 'nullable|string|max:255',
        'cargo_elaboro' => 'nullable|string|max:255',
        'firma_elaboro' => 'nullable|string',
        'nombre_reviso' => 'nullable|string|max:255',
        'cargo_reviso' => 'nullable|string|max:255',
        'firma_reviso' => 'nullable|string',
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