<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkGroup
 * @package Modules\Intranet\Models
 * @version July 1, 2020, 2:28 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection users
 * @property \Illuminate\Database\Eloquent\Collection assignedModules
 * @property string name
 * @property string description
 * @property boolean state
 * @property string url_img_profile
 * @property string|\Carbon\Carbon end_date
 */
class WorkGroup extends Model
{
    use SoftDeletes;

    public $table = 'work_groups';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at',
    ];

    public $fillable = [
        'name',
        'description',
        'state',
        'url_img_profile',
        'end_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'state' => 'boolean',
        'url_img_profile' => 'string',
        'end_date' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(\Modules\Intranet\Models\User::class, 'users_work_groups','work_groups_id' , 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function assignedModules()
    {
        return $this->belongsToMany(\Modules\Intranet\Models\AssignedModule::class, 'work_groups_assigned_modules');
    }
}
