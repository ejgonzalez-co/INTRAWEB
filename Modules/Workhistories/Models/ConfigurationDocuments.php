<?php

namespace Modules\Workhistories\Models;
use App\Http\Controllers\AppBaseController;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ConfigurationDocuments
 * @package Modules\Workhistories\Models
 * @version October 9, 2020, 3:51 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $workHistories
 * @property string $name
 * @property string $description
 * @property string $state
 */
class ConfigurationDocuments extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_config_documents';
    
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
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'state' => 'string'
    ];

    protected $appends = [
      'state_name'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function workHistories()
    {
        return $this->belongsToMany(\Modules\Workhistories\Models\WorkHistory::class, 'work_histories_has_documents');
    }

    public function getStateNameAttribute() {
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('workhistories.state'), 'id', $this->state)->name;
        }
    }
}
