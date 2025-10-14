<?php

namespace Modules\Workhistories\Models;

use App\Http\Controllers\AppBaseController;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class configDocPensioners
 * @package Modules\Workhistories\Models
 * @version December 4, 2020, 11:52 am -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesPDocuments
 * @property string $name
 * @property string $description
 * @property string $state
 * @property integer $users_id
 */
class configDocPensioners extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_config_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description',
        'state',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'state' => 'string',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:45',
        'description' => 'nullable|string|max:45',
        'state' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'state_name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesPDocuments()
    {
        return $this->hasMany(\Modules\Workhistories\Models\WorkHistoriesPDocument::class, 'config_documents_id');
    }

    /**
     * Obtiene el estado(activo-inactivo) 
     * @return
     */
    public function getStateNameAttribute() {
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('workhistories.state'), 'id', $this->state)->name;
        }
    }
}
