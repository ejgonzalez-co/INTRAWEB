<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicRequestStatus;
use App\Repositories\BaseRepository;

/**
 * Class TicRequestStatusRepository
 * @package Modules\HelpTable\Repositories
 * @version June 5, 2021, 10:24 am -05
*/

class TicRequestStatusRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'slug',
        'status_color'
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
        return TicRequestStatus::class;
    }
}
