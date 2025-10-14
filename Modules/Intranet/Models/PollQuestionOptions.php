<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PollQuestionOptions
 * @package Modules\Intranet\Models
 * @version June 27, 2021, 3:38 pm -05
 *
 * @property \Modules\Intranet\Models\IntranetPollsQuestion $intranetPollsQuestions
 * @property string $option_question
 * @property integer $intranet_polls_questions_id
 */
class PollQuestionOptions extends Model
{
      //  use SoftDeletes;

    public $table = 'intranet_polls_questions_options';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'option_question',
        'intranet_polls_questions_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'option_question' => 'string',
        'intranet_polls_questions_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'option_question' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'intranet_polls_questions_id' => 'required'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetPollsQuestions() {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetPollsQuestion::class, 'intranet_polls_questions_id');
    }
}
