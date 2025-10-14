<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\PollQuestions;
use App\Repositories\BaseRepository;

/**
 * Class PollQuestionsRepository
 * @package Modules\Intranet\Repositories
 * @version September 14, 2020, 2:30 pm -05
*/

class PollQuestionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'question',
        'intranet_polls_id'
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
        return PollQuestions::class;
    }
}
