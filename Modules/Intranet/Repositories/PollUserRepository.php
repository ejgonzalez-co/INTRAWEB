<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\PollUser;
use App\Repositories\BaseRepository;

/**
 * Class PollUserRepository
 * @package Modules\Intranet\Repositories
 * @version June 27, 2021, 4:01 pm -05
*/

class PollUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_name',
        'users_id',
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
        return PollUser::class;
    }
}
