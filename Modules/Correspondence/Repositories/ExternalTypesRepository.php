<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\ExternalTypes;
use App\Repositories\BaseRepository;

/**
 * Class ExternalTypesRepository
 * @packageModules\Correspondence\Repositories
 * @version January 12, 2022, 2:25 pm -05
*/

class ExternalTypesRepository extends BaseRepository
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
        return ExternalTypes::class;
    }
}
