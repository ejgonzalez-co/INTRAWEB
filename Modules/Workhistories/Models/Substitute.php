<?php

namespace Modules\Workhistories\Models;
use App\Http\Controllers\AppBaseController;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Substitute
 * @package Modules\Workhistories\Models
 * @version December 6, 2020, 10:51 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesP $workHistoriesP
 * @property string $type_document
 * @property string $number_document
 * @property string $name
 * @property string $surname
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property boolean $city
 * @property string $type_substitute
 * @property string $date_document
 * @property string $birth_date
 * @property string $notification
 * @property string $users_name
 * @property integer $users_id
 * @property integer $work_histories_p_id
 */
class Substitute extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_substitute';
    
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
        'city',
        'depto',
        'type_substitute',
        'date_document',
        'birth_date',
        'notification',
        'total_documents',
        'state',
        'category',
        'users_name',
        'users_id',
        'work_histories_p_id',
        'work_histories_cp_pensionados_id'

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
        'type_substitute' => 'string',
        'notification' => 'string',
        'users_name' => 'string',
        'users_id' => 'integer'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesP()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistPensioner::class, 'work_histories_p_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesCp()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\QuotaPartsPensioner::class, 'work_histories_cp_pensionados_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function historySubstitute()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\SubstituteHistory::class, 'work_histories_p_substitute_id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
        public function substituteHistory()
        {
            return $this->hasMany(\Modules\Workhistories\Models\SubstituteHistory::class, 'work_histories_p_substitute_id');
        }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documentsSubstitute()
    {
        return $this->hasMany(\Modules\Workhistories\Models\DocumentsSubstitute::class, 'work_histories_p_substitute_id')->with(['workHistoriesConfigDocuments']);
    }

    /**
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 **/
    public function documentsNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\DocumentsSubstituteNews::class, 'work_histories_p_substitute_id');
    }

    /**
     * Obtiene el estadode la pension(activo-inactivo) 
     * @return
     */
    public function getStateNameAttribute() {
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('workhistories.pension_state'), 'id', $this->state)->name;
        }
    }

}
