<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoNitritos
 * @package Modules\Leca\Models
 * @version June 16, 2022, 5:44 pm -05
 *
 * @property Modules\Leca\Models\LcEnsayoNitrito $lcEnsayoNitritos
 * @property Modules\Leca\Models\User $users
 * @property integer $users_id
 * @property string $name_user
 * @property string $observation
 * @property integer $lc_ensayo_nitritos_id
 */
class ObservacionesDuplicadoNitritos extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_nitritos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_nitritos_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'name_user' => 'string',
        'observation' => 'string',
        'lc_ensayo_nitritos_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'lc_ensayo_nitritos_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoNitritos() {
        return $this->belongsTo(\Modules\Leca\Models\LcEnsayoNitrito::class, 'lc_ensayo_nitritos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
