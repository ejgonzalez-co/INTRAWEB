<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use App\Http\Controllers\AppBaseController;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QuotaPartsPensioner
 * @package Modules\Workhistories\Models
 * @version December 10, 2020, 5:07 pm -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesCps
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesCpPDocuments
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesCpPDocumentsNews
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesCpPNewsUsers
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesCpPensionadosHes
 * @property string $type_document
 * @property string $number_document
 * @property string $name
 * @property string $surname
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property boolean $gender
 * @property string $group_ethnic
 * @property string $rh
 * @property string $birth_date
 * @property string $notification
 * @property string $level_study_other
 * @property string $level_study
 * @property string $phone_event
 * @property string $name_event
 * @property boolean $state
 * @property string $deceased
 * @property string $observation_deceased
 * @property integer $total_documents
 * @property string $users_name
 * @property string $time_work
 * @property integer $users_id
 */
class QuotaPartsPensioner extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_cp_pensionados';
    
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
        'email',
        'gender',
        'group_ethnic',
        'rh',
        'birth_date',
        'notification',
        'level_study_other',
        'level_study',
        'phone_event',
        'name_event',
        'state',
        'deceased',
        'observation_deceased',
        'total_documents',
        'users_name',
        'time_work',
        'users_id',
        'date_document'
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
        'rh' => 'string',
        'notification' => 'string',
        'level_study_other' => 'string',
        'level_study' => 'string',
        'phone_event' => 'string',
        'name_event' => 'string',
        'deceased' => 'string',
        'observation_deceased' => 'string',
        'total_documents' => 'integer',
        'users_name' => 'string',
        'time_work' => 'string',
        'users_id' => 'integer',
        'date_document'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
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
    public function workHistoriesCps()
    {
        return $this->hasMany(\Modules\Workhistories\Models\QuotaParts::class, 'cp_pensionados_id')->with(['quotaPartsHistory']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesCpPDocuments()
    {
        return $this->hasMany(\Modules\Workhistories\Models\QuotaPartsDocPensioners::class, 'cp_pensionados_id')->with(['configDocuments']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesCpPDocumentsNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\QuotaPartsDocumentsNews::class, 'cp_pensionados_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function quotaPartsNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\QuotaPartsNews::class, 'cp_pensionados_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesCpPNewsUsers()
    {
        return $this->hasMany(\Modules\Workhistories\Models\QuotaPartsNewsUsers::class, 'cp_pensionados_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesCpPensionadosHes()
    {
        return $this->hasMany(\Modules\Workhistories\Models\QuotaPartsPensionerHistory::class, 'cp_pensionados_id');
    }

    
}
