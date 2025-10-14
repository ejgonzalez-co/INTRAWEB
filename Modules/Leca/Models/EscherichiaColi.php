<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EscherichiaColi
 * @package Modules\Leca\Models
 * @version March 8, 2022, 3:34 pm -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_list_trials_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $processo
 * @property string $año
 * @property string $mes
 * @property string $tecnica
 * @property string $ensayo
 * @property string $obervacion
 * @property string $firma
 * @property string $nombre
 * @property string $cargo
 * @property string $user_remplace
 * @property string $user_remplace_admin
 */
class EscherichiaColi extends Model {
    use SoftDeletes;

    public $table = 'lc_escherichia_coli';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_list_trials_id',
        'users_id',
        'user_name',
        'processo',
        'año',
        'mes',
        'tecnica',
        'ensayo',
        'obervacion',
        'firma',
        'nombre',
        'cargo',
        'user_remplace',
        'user_remplace_admin',
        'observations_edit',
        'decimales'
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
        'processo' => 'string',
        'año' => 'string',
        'mes' => 'string',
        'tecnica' => 'string',
        'ensayo' => 'string',
        'obervacion' => 'string',
        'observations_edit' => 'string',
        'firma' => 'string',
        'nombre' => 'string',
        'cargo' => 'string',
        'user_remplace' => 'string',
        'user_remplace_admin' => 'string',
        'decimales' => 'integer'
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
        'processo' => 'nullable|string|max:255',
        'año' => 'nullable|string|max:45',
        'mes' => 'nullable|string|max:45',
        'tecnica' => 'nullable|string|max:255',
        'ensayo' => 'nullable|string|max:255',
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
