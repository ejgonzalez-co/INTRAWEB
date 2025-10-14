<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class TypeEvent
 * @package Modules\Intranet\Models
 * @version June 28, 2021, 1:55 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $intranetEvents
 * @property \Illuminate\Database\Eloquent\Collection $intranetEventsHistories
 * @property string $name
 * @property string $description
 * @property integer $state
 */
class TypeEvent extends Model
{
        use SoftDeletes;

    public $table = 'intranet_type_events';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'description',
        'state'
    ];

    protected $appends = [
        'state_name',
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
        'state' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:45',
        'description' => 'nullable|string',
        'state' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function events() {
        return $this->hasMany(\Modules\Intranet\Models\Event::class, 'intranet_type_events_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function eventsHistories() {
        return $this->hasMany(\Modules\Intranet\Models\EventsHistory::class, 'intranet_type_events_id');
    }

    /**
     * Obtiene el nombre del estado
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('intranet.type_event_status'), 'id', $this->state)->name;
    }
}
