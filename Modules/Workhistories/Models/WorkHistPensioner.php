<?php

namespace Modules\Workhistories\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkHistPensioner
 * @package Modules\Workhistories\Models
 * @version December 3, 2020, 5:38 pm -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesPDocuments
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesPDocumentsNews
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesPDocumentsNewsUsers
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesPHistories
 * @property string $type_document
 * @property string $number_document
 * @property string $name
 * @property string $surname
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property boolean $gender
 * @property string $group_ethnic
 * @property string $birth_date
 * @property string $notification
 * @property string $level_study
 * @property string $phone_event
 * @property string $name_event
 * @property boolean $state
 * @property integer $total_documents
 * @property string $users_name
 * @property integer $users_id
 */
class WorkHistPensioner extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'type_document',
        'number_document',
        'name',
        'surname',
        'address',
        'phone',
        'phone2',
        'email',
        'gender',
        'group_ethnic',
        'birth_date',
        'notification',
        'level_study',
        'phone_event',
        'name_event',
        'state',
        'total_documents',
        'users_id',
        'users_name',
        'level_study_other',
        'rh',
        'date_document',
        'date_admission',
        'type_laboral',
        'level_laboral',
        'denomination_laboral',
        'dependencias_id',
        'numer_account',
        'area',
        'salary',
        'health',
        'pension',
        'layoffs',
        'embargo',
        'embargo_owner',
        'embargo_value',
        'address_event',
        'relationship_event',
        'dependencias_id',
        'dependencias_name',
        'grade_laboral',
        'state_civil',
        'arl',
        'observation_deceased',
        'deceased',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type_document' => 'string',
        'number_document' => 'string',
        'name' => 'string',
        'surname' => 'string',
        'address' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'group_ethnic' => 'string',
        'notification' => 'string',
        'level_study' => 'string',
        'phone_event' => 'string',
        'name_event' => 'string',
        'state' => 'boolean',
        'total_documents' => 'integer',
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
    public function users()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\User::class, 'users_id');
    }


 /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function workHistoriesConfigDocuments()
    {
        return $this->belongsToMany(\Modules\Workhistories\Models\configDocPensioners::class, 'work_histories_has_documents');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesHistory()
    {
        return $this->hasMany(\Modules\Workhistories\Models\HistoryPensioner::class, 'work_histories_p_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documentsNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\DocumentsPensionersNews::class, 'work_histories_p_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistorieNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\NewsHistoriesPen::class, 'work_histories_p_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistorieDocuments()
    {
        return $this->hasMany(\Modules\Workhistories\Models\DocumentsPensioners::class, 'work_histories_p_id')->with(['workHistoriesConfigDocuments']);
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistorieFamilies()
    {
        return $this->hasMany(\Modules\Workhistories\Models\FamilyPensioner::class, 'work_histories_p_id');
    }

            /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historyRequest()
    {
        return $this->hasMany(\Modules\Workhistories\Models\WorkRequestHistory::class, 'work_histories_id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function requestWorkHistorie()
    {
        return $this->hasMany(\Modules\Workhistories\Models\WorkRequest::class, 'work_histories_id');
    }
}
