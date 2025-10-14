<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserOtherDependence
 * @package Modules\ImprovementPlans\Models
 * @version September 6, 2023, 2:21 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\Dependencia $idDependece
 * @property \Modules\ImprovementPlans\Models\User $users
 * @property integer $users_id
 * @property integer $id_dependece
 */
class UserOtherDependence extends Model
{
        use SoftDeletes;

    public $table = 'pm_users_others_dependences';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'nombre',
        'dependence_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'id_dependence' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idDependece() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\Dependence::class, 'dependence_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\User::class, 'users_id');
    }
}
