<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkRequest
 * @package Modules\Workhistories\Models
 * @version October 20, 2021, 10:48 am -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property Modules\Workhistories\Models\WorkHistory $workHistories
 * @property Modules\Workhistories\Models\WorkHistoriesP $workHistoriesP
 * @property \Illuminate\Database\Eloquent\Collection $workHistoryCvs
 * @property integer $users_id
 * @property integer $work_histories_id
 * @property integer $work_histories_p_id
 * @property integer $work_histories_p_users_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $consultation_time
 * @property string $answer
 * @property string $reason_consultation
 * @property string $condition
 * @property string $date_start
 * @property string $date_final
 */
class WorkRequest extends Model
{
        use SoftDeletes;

    public $table = 'work_request_cv';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    protected $appends = [

        'change_state',
    ];
    //Cambia el estado de la solicitudes
    public function getChangeStateAttribute(){
        $date = date('Y-m-d H:i:s');
        //Verifica que solicitudes estan en estas condiciones
        if($this->condition=="Aprobado" || $this->condition=="En ejecución"){
            //recupero la fecha y la hora actual
            $date = date('Y-m-d H:i:s');
            //Verifica que la hora actual este entre el rango
            if(strtotime($this->date_start) < strtotime($date) &&  strtotime($this->date_final) > strtotime($date)){
                //Cambia el estado de la solicitud
                $this->condition='En ejecución';
                //Se guarda el objeto
                $this->save();
            }
            //Verifica que si la fecha actual es mayor a la fecha final cambie de estado a finalizado
            if(strtotime($this->date_final) < strtotime($date)){
                $this->condition='Finalizado';
                //Se guarda el estado
                $this->save();
            }
        }
        return true;
    }


    public $fillable = [
        'users_id',
        'work_histories_id',
        'work_histories_p_id',
        'work_histories_p_users_id',
        'user_id',
        'user_name',
        'state',
        'create_user',
        'dependencia_user_create',
        'document_user',
        'consultation_time',
        'answer',
        'reason_consultation',
        'condition',
        'date_start',
        'date_final'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'work_histories_id' => 'integer',
        'work_histories_p_id' => 'integer',
        'work_histories_p_users_id' => 'integer',
        'user_id' => 'integer',
        'user_name' => 'string',
        'document_user' => 'string',
        'state' => 'string',
        'create_user' => 'string',
        'dependencia_user_create' => 'string',
        'consultation_time' => 'string',
        'answer' => 'string',
        'reason_consultation' => 'string',
        'condition' => 'string',
        'date_start' => 'string',
        'date_final' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'work_histories_id' => 'nullable',
        'work_histories_p_id' => 'nullable',
        'work_histories_p_users_id' => 'nullable',
        'user_id' => 'nullable',
        'user_name' => 'nullable|string|max:250',
        'document_user' => 'nullable|string|max:250',
        'consultation_time' => 'nullable|string|max:255',
        'create_user' => 'nullable|string|max:255',
        'dependencia_user_create' => 'nullable|string|max:255',
        'answer' => 'nullable|string',
        'reason_consultation' => 'nullable|string',
        'condition' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'date_start' => 'nullable',
        'date_final' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id')->with(['dependencies']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistories() {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesActive::class, 'work_histories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesP() {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistPensioner::class, 'work_histories_p_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoryCvs() {
        return $this->hasMany(\Modules\Workhistories\Models\WorkHistoryCv::class, 'work_request_cv_id');
    }
}
