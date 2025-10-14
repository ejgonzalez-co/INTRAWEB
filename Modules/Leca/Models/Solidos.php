<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Solidos
 * @package Modules\Leca\Models
 * @version March 11, 2022, 10:12 am -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_list_trials_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $proceso
 * @property string $ensayo_uno
 * @property string $ensayo_dos
 * @property string $documento_referencia_uno
 * @property string $documento_referencia_dos
 * @property string $obervacion
 * @property string $firma
 * @property string $nombre
 * @property string $cargo
 * @property string $user_remplace
 * @property string $user_remplace_admin
 */
class Solidos extends Model {
    use SoftDeletes;

    public $table = 'lc_solidos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_list_trials_id',
        'users_id',
        'user_name',
        'proceso',
        'ensayo_uno',
        'ensayo_dos',
        'documento_referencia_uno',
        'documento_referencia_dos',
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
        'ensayo_uno' => 'string',
        'ensayo_dos' => 'string',
        'documento_referencia_uno' => 'string',
        'documento_referencia_dos' => 'string',
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
        'ensayo_uno' => 'nullable|string|max:255',
        'ensayo_dos' => 'nullable|string|max:255',
        'valor_esperado_lcm' => 'nullable|string|max:255',
        'documento_referencia_uno' => 'nullable|string|max:255',
        'documento_referencia_dos' => 'nullable|string|max:255',
        'obervacion' => 'nullable|string',
        'observations_edit' => 'nullable|string|min:30',
        'firma' => 'nullable|string',
        'nombre' => 'nullable|string|max:255',
        'cargo' => 'nullable|string|max:255',
        'user_remplace' => 'nullable|string|max:255',
        'user_remplace_admin' => 'nullable|string|max:255',
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
