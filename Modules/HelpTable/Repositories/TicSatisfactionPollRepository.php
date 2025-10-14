<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicSatisfactionPoll;
use App\Repositories\BaseRepository;

/**
 * Class TicSatisfactionPollRepository
 * @package Modules\HelpTable\Repositories
 * @version June 5, 2021, 11:42 am -05
*/

class TicSatisfactionPollRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ht_tic_requests_id',
        'users_id',
        'functionary_id',
        'question',
        'reply'
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
        return TicSatisfactionPoll::class;
    }
}
