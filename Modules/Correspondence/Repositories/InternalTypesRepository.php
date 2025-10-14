<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\InternalTypes;
use App\Repositories\BaseRepository;

/**
 * Class InternalTypesRepository
 * @packageModules\Correspondence\Repositories
 * @version January 12, 2022, 2:25 pm -05
*/

class InternalTypesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'template',
        'prefix',
        'variables'
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
        return InternalTypes::class;
    }
}
