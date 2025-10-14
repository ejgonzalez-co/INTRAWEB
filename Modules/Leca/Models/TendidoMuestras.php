<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TendidoMuestras
 * @package Modules\Leca\Models
 * @version May 10, 2022, 4:39 pm -05
 *
 * @property Modules\Leca\Models\LcSampleTakingHasLcListTrial $lcSampleTakingHasLcListTrials
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_list_trials_id
 * @property integer $users_id
 */
class TendidoMuestras extends Model {
    use SoftDeletes;

    public $table = 'lc_tendido_muestras';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_list_trials_id',
        'lc_sample_taking_id',
        'users_id',
        'estado',
        'codigo_muestra',
        'fecha_finalizado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_list_trials_id' => 'integer',
        'lc_sample_taking_id' => 'integer',
        'users_id' => 'integer',
        'estado' => 'string',
        'codigo_muestra' => 'string',
        'fecha_finalizado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'lc_list_trials_id' => 'required',
        'lc_sample_taking_id' => 'required',
        'users_id' => 'required',
        'estado' => 'nullable|string|max:255',
        'codigo_muestra' => 'nullable|string|max:255',
        'fecha_finalizado' => 'nullable'

    ];


    protected $appends = [
        'codigo_muestra',
    ];

    public function getCodigoMuestraAttribute(){
       
        return $this->muestras->sample_reception_code;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ensayos() {
        return $this->belongsTo(\Modules\Leca\Models\ListTrials::class, 'lc_list_trials_id');
    }


        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function muestras() {
        return $this->belongsTo(\Modules\Leca\Models\SampleTaking::class, 'lc_sample_taking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
