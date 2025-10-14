<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PollQuestions
 * @package Modules\Intranet\Models
 * @version September 14, 2020, 2:30 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetPoll $intranetPolls
 * @property \Illuminate\Database\Eloquent\Collection $intranetPollsQuestionsOptions
 * @property boolean $type
 * @property string $question
 * @property integer $intranet_polls_id
 */
class PollQuestions extends Model
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
        'question' => 'required'

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetPolls()
    {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetPoll::class, 'intranet_polls_id');
    }

    /*
    public function intranetPollsQuestionsOptions()
    {
        return $this->hasMany(\Modules\Intranet\Models\IntranetPollsQuestionsOption::class, 'poll_questions_id');
    }**/

    public function intranetPollsQuestionsOptions()
    {
       // return $this->belongsToMany(\Modules\Intranet\Models\PollQuestionsOption::class, 'intranet_polls_questions_options', 'poll_questions_id', 'id')->withPivot('option_question');
    return $this->hasMany(\Modules\Intranet\Models\PollQuestionsOption::class);

    }
}
