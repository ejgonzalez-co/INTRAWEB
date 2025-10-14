<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicPollQuestion;
use App\Repositories\BaseRepository;

/**
 * Class TicPollQuestionRepository
 * @package Modules\HelpTable\Repositories
 * @version June 8, 2021, 3:33 pm -05
*/

class TicPollQuestionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'question_number',
        'question',
        'answer_option'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TicPollQuestion::class;
    }
}
