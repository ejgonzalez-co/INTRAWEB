<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PollAnswer
 * @package Modules\Intranet\Models
 * @version June 28, 2021, 1:25 pm -05
 *
 * @property \Modules\Intranet\Models\User $users
 * @property \Modules\Intranet\Models\IntranetPollsQuestion $intranetPollsQuestions
 * @property \Modules\Intranet\Models\IntranetPoll $intranetPolls
 * @property string $answer
 * @property string $users_name
 * @property integer $intranet_polls_id
 * @property integer $intranet_polls_questions_id
 * @property integer $users_id
 */
class PollAnswer extends Model
{
        use SoftDeletes;

    public $table = 'intranet_polls_answers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'answer',
        'users_name',
        'intranet_polls_id',
        'intranet_polls_questions_id',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'answer' => 'string',
        'users_name' => 'string',
        'intranet_polls_id' => 'integer',
        'intranet_polls_questions_id' => 'integer',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetPollsQuestions() {
        return $this->belongsTo(\Modules\Intranet\Models\PollQuestion::class, 'intranet_polls_questions_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function intranetPolls() {
        return $this->belongsTo(\Modules\Intranet\Models\Poll::class, 'intranet_polls_id');
    }
}
