<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistorialFuncionarioRutina
 * @package Modules\Leca\Models
 * @version April 29, 2022, 3:57 pm -05
 *
 * @property string $name
 * @property string $name_user_p
 * @property string $name_user_s
 * @property string $observacion
 */
class HistorialFuncionarioRutina extends Model {
    use SoftDeletes;

    public $table = 'lc_historia_funcionarios_rutina';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'name_user_p',
        'name_user_s',
        'observacion',
        'lc_monthly_routines_officials_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_monthly_routines_officials_id' => 'integer',
        'name' => 'string',
        'name_user_p' => 'string',
        'name_user_s' => 'string',
        'observacion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'lc_monthly_routines_officials_id' => 'required',
        'name_user_p' => 'nullable|string|max:255',
        'name_user_s' => 'nullable|string|max:255',
        'observacion' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function funcionarios() {
        return $this->belongsTo(\Modules\Leca\Models\MonthlyRoutinesOfficials::class, 'lc_monthly_routines_officials_id');
    }

    
}
