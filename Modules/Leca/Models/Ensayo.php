<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ensayo
 * @package Modules\Leca\Models
 * @version April 29, 2022, 2:43 pm -05
 *
 * @property Modules\Leca\Models\LcListTrial $lcListTrials
 * @property Modules\Leca\Models\LcSampleTaking $lcSampleTaking
 * @property integer $lc_list_trials_id
 * @property string $nombre_usuario
 * @property string|\Carbon\Carbon $fecha_analisis
 * @property string $estado
 * @property integer $user_id
 */
class Ensayo extends Model {
    use SoftDeletes;

    public $table = 'lc_sample_taking_has_lc_list_trials';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_list_trials_id',
        'nombre_usuario',
        'fecha_analisis',
        'estado',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'lc_sample_taking_id' => 'integer',
        'lc_list_trials_id' => 'integer',
        'nombre_usuario' => 'string',
        'fecha_analisis' => 'datetime',
        'estado' => 'string',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_list_trials_id' => 'required',
        'nombre_usuario' => 'nullable|string|max:255',
        'fecha_analisis' => 'nullable',
        'estado' => 'nullable|string|max:255',
        'user_id' => 'nullable',
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
    public function lcSampleTaking() {
        return $this->belongsTo(\Modules\Leca\Models\LcSampleTaking::class, 'lc_sample_taking_id');
    }
}
