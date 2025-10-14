<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RolPermission
 * @package Modules\ImprovementPlans\Models
 * @version August 24, 2023, 11:01 am -05
 *
 * @property \Modules\ImprovementPlans\Models\Role $role
 * @property integer $role_id
 * @property string $module
 * @property boolean $can_manage
 * @property boolean $can_generate_reports
 * @property boolean $only_consultation
 */
class RolPermission extends Model
{
        use SoftDeletes;

    public $table = 'pm_rol_permissions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'role_id',
        'module',
        'can_manage',
        'can_generate_reports',
        'only_consultation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'module' => 'string',
        'can_manage' => 'boolean',
        'can_generate_reports' => 'boolean',
        'only_consultation' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'role_id' => 'required',
        'module' => 'nullable|string|max:45',
        'can_manage' => 'nullable|boolean',
        'can_generate_reports' => 'nullable|boolean',
        'only_consultation' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function role() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\Role::class, 'role_id');
    }
}
