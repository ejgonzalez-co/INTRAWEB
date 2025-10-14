<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\PollQuestion;
use App\Repositories\BaseRepository;

/**
 * Class PollQuestionRepository
 * @package Modules\Intranet\Repositories
 * @version June 27, 2021, 2:30 pm -05
*/

class PollQuestionRepository extends BaseRepository
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
        return PollQuestion::class;
    }
}
