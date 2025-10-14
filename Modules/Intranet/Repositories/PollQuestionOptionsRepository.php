<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\PollQuestionOptions;
use App\Repositories\BaseRepository;

/**
 * Class PollQuestionOptionsRepository
 * @package Modules\Intranet\Repositories
 * @version June 27, 2021, 3:38 pm -05
*/

class PollQuestionOptionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'option_question',
        'intranet_polls_questions_id'
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
        return PollQuestionOptions::class;
    }
}
