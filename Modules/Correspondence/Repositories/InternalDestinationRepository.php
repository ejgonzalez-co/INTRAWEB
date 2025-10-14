<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\InternalDestination;
use App\Repositories\BaseRepository;

/**
 * Class InternalDestinationRepository
 * @packageModules\Correspondence\Repositories
 * @version January 19, 2022, 11:58 am -05
*/

class InternalDestinationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'is_readed',
        'times',
        'correspondence_internal_id',
        'users_id',
        'work_groups_id',
        'cargos_id',
        'dependencias_id'
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
        return InternalDestination::class;
    }
}
