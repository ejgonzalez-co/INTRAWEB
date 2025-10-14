<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TicPollQuestion
 * @package Modules\HelpTable\Models
 * @version June 8, 2021, 3:33 pm -05
 *
 * @property string $question_number
 * @property string $question
 * @property string $answer_option
 */
class TicPollQuestion extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_poll_questions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'question_number',
        'question',
        'answer_option'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'question_number' => 'string',
        'question' => 'string',
        'answer_option' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'question_number' => 'nullable|string|max:20',
        'question' => 'nullable|string|max:100',
        'answer_option' => 'nullable|string',
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

    
}
