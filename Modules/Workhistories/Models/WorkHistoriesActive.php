<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkHistoriesActive
 * @package Modules\Workhistories\Models
 * @version October 9, 2020, 3:19 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesConfigDocuments
 * @property string $type_document
 * @property string $number_document
 * @property string $name
 * @property string $surname
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property boolean $gender
 * @property string $group_ethnic
 * @property string|\Carbon\Carbon $birth_date
 * @property string $notification
 * @property string $level_study
 */
class WorkHistoriesActive extends Model
{
    use SoftDeletes;

    public $table = 'work_histories';
    
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
        'arl'
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
        'phone2' => 'string',
        'email' => 'string',
        'gender' => 'string',
        'group_ethnic' => 'string',
        'notification' => 'string',
        'level_study' => 'string',
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
    public function workHistoriesConfigDocuments()
    {
        return $this->belongsToMany(\Modules\Workhistories\Models\WorkHistoriesConfigDocument::class, 'work_histories_has_documents');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesHistory()
    {
        return $this->hasMany(\Modules\Workhistories\Models\WorkHistoriesHistory::class, 'work_histories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documentsNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\DocumentsNews::class, 'work_histories_id');
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

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistorieNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\NewsHistories::class, 'work_histories_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistorieDocuments()
    {
        return $this->hasMany(\Modules\Workhistories\Models\Documents::class, 'work_histories_id')->with(['workHistoriesConfigDocuments']);
    }



    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistorieFamilies()
    {
        return $this->hasMany(\Modules\Workhistories\Models\Family::class, 'work_histories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesNovelties() {
        return $this->hasMany(\Modules\Workhistories\Models\WorkHistoriesNovelty::class, 'work_histories_id');
    }
}
