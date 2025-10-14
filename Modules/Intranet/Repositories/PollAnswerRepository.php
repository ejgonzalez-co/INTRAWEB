<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\PollAnswer;
use App\Repositories\BaseRepository;

/**
 * Class PollAnswerRepository
 * @package Modules\Intranet\Repositories
 * @version June 28, 2021, 1:25 pm -05
*/

class PollAnswerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'answer',
        'users_name',
        'intranet_polls_id',
        'intranet_polls_questions_id',
        'users_id'
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
        return PollAnswer::class;
    }
}
