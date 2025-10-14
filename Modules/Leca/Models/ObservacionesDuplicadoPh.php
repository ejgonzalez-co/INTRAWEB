<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ObservacionesDuplicadoPh
 * @package Modules\leca\Models
 * @version January 31, 2023, 10:03 am -05
 *
 * @property Modules\leca\Models\LcEnsayoPh $lcEnsayoPh
 * @property string $name_user
 * @property string $observation
 * @property integer $lc_ensayo_ph_id
 */
class ObservacionesDuplicadoPh extends Model {
    use SoftDeletes;

    public $table = 'lc_observaciones_duplicado_ph';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_ph_id'
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
        'lc_ensayo_ph_id' => 'integer'
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
        'lc_ensayo_ph_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoPh() {
        return $this->belongsTo(\Modules\leca\Models\LcEnsayoPh::class, 'lc_ensayo_ph_id');
    }
}
