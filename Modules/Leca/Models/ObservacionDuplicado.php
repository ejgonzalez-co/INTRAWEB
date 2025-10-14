<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionDuplicado
 * @package Modules\Leca\Models
 * @version May 31, 2022, 8:03 am -05
 *
 * @property Modules\Leca\Models\LcEnsayoAluminio $lcEnsayoAluminio
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_ensayo_aluminio_id
 * @property integer $users_id
 * @property string $name_user
 * @property string $observation
 */
class ObservacionDuplicado extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_ensayo_aluminio_id',
        'users_id',
        'name_user',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_ensayo_aluminio_id' => 'integer',
        'users_id' => 'integer',
        'name_user' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_ensayo_aluminio_id' => 'required',
        
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoAluminio() {
        return $this->belongsTo(\Modules\Leca\Models\LcEnsayoAluminio::class, 'lc_ensayo_aluminio_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }
}
