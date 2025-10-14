<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Poll;
use App\Repositories\BaseRepository;

/**
 * Class PollRepository
 * @package Modules\Intranet\Repositories
 * @version June 27, 2021, 12:20 pm -05
*/

class PollRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'description',
        'date_open',
        'date_close',
        'creator',
        'state',
        'users_name',
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
        return Poll::class;
    }
}
