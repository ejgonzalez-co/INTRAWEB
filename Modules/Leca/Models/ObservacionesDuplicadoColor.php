<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoColor
 * @package Modules\leca\Models
 * @version January 25, 2023, 4:42 pm -05
 *
 * @property Modules\leca\Models\LcEnsayoColor $lcEnsayoColor
 * @property Modules\leca\Models\User $users
 * @property string $name_user
 * @property string $observation
 * @property integer $users_id
 * @property integer $lc_ensayo_color_id
 */
class ObservacionesDuplicadoColor extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_color';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name_user',
        'observation',
        'users_id',
        'lc_ensayo_color_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_user' => 'string',
        'observation' => 'string',
        'users_id' => 'integer',
        'lc_ensayo_color_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_user' => 'nullable|string|max:120',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        // 'users_id' => 'required',
        'lc_ensayo_color_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoColor() {
        return $this->belongsTo(\Modules\leca\Models\LcEnsayoColor::class, 'lc_ensayo_color_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }
}
