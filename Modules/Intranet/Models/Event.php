<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;
use DateTimeInterface;

/**
 * Class Event
 * @package Modules\Intranet\Models
 * @version June 28, 2021, 1:58 am -05
 *
 * @property \Modules\Intranet\Models\IntranetTypeEvent $intranetTypeEvents
 * @property \Illuminate\Database\Eloquent\Collection $intranetEventsHistories
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property \Illuminate\Database\Eloquent\Collection $workGroups
 * @property integer $intranet_type_events_id
 * @property integer $type_category
 * @property string $title
 * @property string $description
 * @property string|\Carbon\Carbon $initial_date
 * @property time $initial_hour
 * @property time $end_hour
 * @property integer $duration
 * @property integer $state
 */
class Event extends Model
{
        use SoftDeletes;

    public $table = 'intranet_events';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'intranet_type_events_id',
        'type_category',
        'title',
        'description',
        'initial_date',
        'initial_hour',
        'end_hour',
        'duration',
        'state'
    ];

    protected $appends = [
        'type_category_name',
        'duration_name',
        'state_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'intranet_type_events_id' => 'integer',
        'type_category' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'duration' => 'integer',
        'state' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'intranet_type_events_id' => 'required',
        'type_category' => 'required|integer',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'initial_date' => 'required',
        'initial_hour' => 'required',
        'duration' => 'required|integer',
        'state' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function typeEvents() {
        return $this->belongsTo(\Modules\Intranet\Models\TypeEvent::class, 'intranet_type_events_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function eventsHistories() {
        return $this->hasMany(\Modules\Intranet\Models\EventHistory::class, 'intranet_events_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users() {
        return $this->hasMany(\Modules\Intranet\Models\EventUser::class, 'intranet_events_id')->with('users');
        // return $this->belongsToMany(\Modules\Intranet\Models\User::class, 'intranet_users_events', 'intranet_events_id', 'users_id')
        // ->with(['dependencies']);
        // return $this->belongsToMany(\Modules\Intranet\Models\User::class, 'intranet_users_events');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function workGroups() {
        // return $this->belongsToMany(\Modules\Intranet\Models\WorkGroup::class, 'intranet_work_groups_events');
        return $this->belongsToMany(\Modules\Intranet\Models\WorkGroup::class, 'intranet_work_groups_events', 'intranet_events_id', 'work_groups_id');
    }

    /**
     * Obtiene el nombre del tipo de evento
     * @return 
     */
    public function getTypeCategoryNameAttribute() {
        return AppBaseController::getObjectOfList(config('intranet.event_category_type'), 'id', $this->type_category)->name;
    }

    /**
     * Obtiene el tiempo de duracion del evento
     * @return 
     */
    public function getDurationNameAttribute() {
        return AppBaseController::getObjectOfList(config('intranet.duration_event'), 'id', $this->duration)->name;
    }

    /**
     * Obtiene el nombre del estado del evento
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('intranet.event_status'), 'id', $this->state)->name;
    }
}
