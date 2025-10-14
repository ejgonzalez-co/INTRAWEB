<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Dependence
 * @package Modules\ImprovementPlans\Models
 * @version August 31, 2023, 8:10 am -05
 *
 * @property \Modules\ImprovementPlans\Models\Sede $idSede
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property integer $id_sede
 * @property string $codigo
 * @property string $nombre
 * @property string $codigo_oficina_productora
 * @property integer $cf_user_id
 */
class Dependence extends Model
{
        use SoftDeletes;

    public $table = 'dependencias';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'id_sede',
        'codigo',
        'nombre',
        'codigo_oficina_productora',
        'cf_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_sede' => 'integer',
        'codigo' => 'string',
        'nombre' => 'string',
        'codigo_oficina_productora' => 'string',
        'cf_user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_sede' => 'required',
        'codigo' => 'nullable|string|max:45',
        'nombre' => 'required|string|max:80',
        'codigo_oficina_productora' => 'nullable|string|max:45',
        'cf_user_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idSede() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\Sede::class, 'id_sede');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function users() {
        return $this->hasMany(\Modules\ImprovementPlans\Models\User::class, 'id_dependencia');
    }
}
