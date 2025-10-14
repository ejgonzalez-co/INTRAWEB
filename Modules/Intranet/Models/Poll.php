<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;
use Auth;

/**
 * Class Poll
 * @package Modules\Intranet\Models
 * @version June 27, 2021, 12:20 pm -05
 *
 * @property \Modules\Intranet\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $intranetPollsAnswers
 * @property \Illuminate\Database\Eloquent\Collection $intranetPollsQuestions
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property \Illuminate\Database\Eloquent\Collection $intranetPollsWorkGroups
 * @property string $title
 * @property string $description
 * @property string $date_open
 * @property string $date_close
 * @property string $creator
 * @property boolean $state
 * @property string $users_name
 * @property integer $users_id
 */
class Poll extends Model
{
        use SoftDeletes;

    public $table = 'intranet_polls';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'title',
        'description',
        'date_open',
        'date_close',
        'creator',
        'state',
        'users_name',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',

        'creator' => 'string',
        'state' => 'integer',
        'users_name' => 'string',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
       

    ];

    protected $appends = [
        'state_name',
        'state_colour',
        'was_answered',
        'total_answered',
        'count_questions',
        'in_range_date'   
   
    ];


    /**
     * Obtiene el nombre del estado
     * @return
     */
    public function getStateNameAttribute() {
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('intranet.state'), 'id', $this->state)->name;
        }
    }

    /**
     * Obtiene la clase del estado
     * @return
     */
    public function getStateColourAttribute() {
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('intranet.state'), 'id', $this->state)->colour;
        }
    }

    /**
     * retorna las respuestas de las preguntas y las preguntas
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/

    public function intranetPollsAnswers() {
        return $this->hasMany(\Modules\Intranet\Models\PollAnswer::class, 'intranet_polls_id')->with("intranetPollsQuestions");
    }

    /**
     * Retorna las respuestas preguntas de la encuesta
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetPollsAnswersNoQuestion() {
        return $this->hasMany(\Modules\Intranet\Models\PollAnswer::class, 'intranet_polls_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetPollsQuestions() {
        return $this->hasMany(\Modules\Intranet\Models\PollQuestion::class, 'intranet_polls_id')->with(['intranetPollsQuestionsOptions']);
    }

    /**
     * Retorna los usuarios de la encuesta de la tabla puente
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function users() {
        
        return $this->hasMany(\Modules\Intranet\Models\PollUser::class, 'intranet_polls_id')->with('users');
    }

    public function intranetPollsWorkGroups() {
        
        return $this->hasMany(\Modules\Intranet\Models\PollUser::class, 'intranet_polls_id')->with('users');
    }
    
    /**
     * Retorna los usuarios de la encuesta
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function usersBelongs() {
        
         return $this->belongsToMany(\Modules\Intranet\Models\User::class, 'intranet_polls_users', 'intranet_polls_id', 'users_id');

    }

    /**
     * obtiene los grupos de la encuesta
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workGroups()
    {
        return $this->belongsToMany(\Modules\Intranet\Models\WorkGroup::class, 'intranet_polls_work_groups', 'intranet_polls_id', 'work_groups_id');

    }

    /**
     * obtiene y valida si el usuario en sesion ya respondio la encuesta
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function getWasAnsweredAttribute()
    {
        $countAnswers = $this->intranetPollsAnswers()->where('users_id','=',Auth::user()->id)->count();
        $wasAnswered = false;

        if($countAnswers>0){
            $wasAnswered = true;
        }
        return $wasAnswered;

    }
    
    /**
     * obtiene el total de respuestas de una encuesta por usuario
     **/
    public function getTotalAnsweredAttribute()
    {
        $countAnswers = $this->intranetPollsAnswersNoQuestion()->groupBy('users_id')->get()->toArray();

        return $countAnswers;

    }

    /**
     * obtiene el total de preguntas de una encuesta para habilitar la opcion de publicar
     **/
    public function getCountQuestionsAttribute()
    {
        $countQuestions = $this->intranetPollsQuestions()->count();
        return $countQuestions;
    }

    /**
     * obtiene y valida si una encuesta esta entre los rangos de fecha
     **/
    public function getInRangeDateAttribute()
    {
        //obtiene fechas de modelo
        $dateOpen = strtotime($this->date_open);
        $dateClose = strtotime($this->date_close);

        $dateNow = strtotime(date("Y-m-d"));
            
        $inRange =false;
        //valida con la fecha de hoy
        if ($dateNow >= $dateOpen && $dateNow <= $dateClose) {
            $inRange = true;
        }
        return $inRange;
    }
    
}
