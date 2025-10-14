<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class EventHistory
 * @package Modules\Intranet\Models
 * @version June 28, 2021, 1:59 am -05
 *
 * @property \Modules\Intranet\Models\IntranetEvent $intranetEvents
 * @property \Modules\Intranet\Models\IntranetTypeEvent $intranetTypeEvents
 * @property integer $intranet_type_events_id
 * @property integer $intranet_events_id
 * @property integer $type_category
 * @property string $title
 * @property string $description
 * @property string|\Carbon\Carbon $initial_date
 * @property time $initial_hour
 * @property time $end_hour
 * @property integer $duration
 * @property integer $state
 */
class EventHistory extends Model
{
        use SoftDeletes;

    public $table = 'intranet_events_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'intranet_type_events_id',
        'intranet_events_id',
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
        'type_events_name',
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
        'intranet_events_id' => 'integer',
        'type_category' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'initial_date' => 'datetime',
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
        'intranet_events_id' => 'required',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetEvents() {
        return $this->belongsTo(\Modules\Intranet\Models\Event::class, 'intranet_events_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetTypeEvents() {
        return $this->belongsTo(\Modules\Intranet\Models\TypeEvent::class, 'intranet_type_events_id');
    }

    /**
     * Obtiene el nombre de la dependencia
     */
    public function getTypeEventsNameAttribute() {
        return $this->intranetTypeEvents->name ?? '';
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
