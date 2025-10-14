<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class PollQuestion
 * @package Modules\Intranet\Models
 * @version June 27, 2021, 2:30 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetPoll $intranetPolls
 * @property \Illuminate\Database\Eloquent\Collection $intranetPollsAnswers
 * @property \Illuminate\Database\Eloquent\Collection $intranetPollsQuestionsOptions
 * @property boolean $type
 * @property string $question
 * @property integer $intranet_polls_id
 */
class PollQuestion extends Model
{
        use SoftDeletes;

    public $table = 'intranet_polls_questions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'type',
        'question',
        'intranet_polls_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'question' => 'string',
        'intranet_polls_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'required',
        'question' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'type_name'
    ];

    
    /**
     * Obtiene el nombre del estado
     * @return
     */
    public function getTypeNameAttribute() {
        if (!empty($this->type)) {
            return AppBaseController::getObjectOfList(config('intranet.type_question_poll'), 'id', $this->type)->name;
        }
    }


        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetPolls() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetPoll::class, 'intranet_polls_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetPollsAnswers() {
        return $this->hasMany(\Modules\Intranet\Models\PollAnswer::class, 'intranet_polls_questions_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function intranetPollsQuestionsOptions() {
        return $this->hasMany(\Modules\Intranet\Models\PollQuestionOptions::class, 'intranet_polls_questions_id');
    }
}
