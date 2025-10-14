<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicTypeRequest;
use App\Repositories\BaseRepository;

/**
 * Class TicTypeRequestRepository
 * @package Modules\HelpTable\Repositories
 * @version June 5, 2021, 8:27 am -05
*/

class TicTypeRequestRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'unit_time',
        'type_term',
        'term',
        'early',
        'description'
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
        return TicTypeRequest::class;
    }
}
