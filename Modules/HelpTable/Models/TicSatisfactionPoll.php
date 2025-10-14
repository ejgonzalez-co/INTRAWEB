<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TicSatisfactionPoll
 * @package Modules\HelpTable\Models
 * @version June 5, 2021, 11:42 am -05
 *
 * @property \Modules\HelpTable\Models\TicRequest $htTicRequests
 * @property \Modules\HelpTable\Models\User $users
 * @property \Modules\HelpTable\Models\User $functionary
 * @property integer $ht_tic_requests_id
 * @property integer $users_id
 * @property integer $functionary_id
 * @property string $question
 * @property string $reply
 */
class TicSatisfactionPoll extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_satisfaction_poll';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'ht_tic_requests_id',
        'users_id',
        'functionary_id',
        'question',
        'reply'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_requests_id' => 'integer',
        'users_id' => 'integer',
        'functionary_id' => 'integer',
        'question' => 'string',
        'reply' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_requests_id' => 'nullable',
        'users_id' => 'nullable',
        'functionary_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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
    public function ticRequests() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicRequest::class, 'ht_tic_requests_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function functionary() {
        return $this->belongsTo(\App\User::class, 'functionary_id');
    }
}
