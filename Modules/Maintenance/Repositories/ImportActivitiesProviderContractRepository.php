<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ImportActivitiesProviderContract;
use App\Repositories\BaseRepository;

/**
 * Class ImportActivitiesProviderContractRepository
 * @package Modules\Maintenance\Repositories
 * @version June 3, 2021, 10:05 am -05
*/

class ImportActivitiesProviderContractRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code',
        'value',
        'description',
        'file_import',
        'mant_provider_contract_id'
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
        return ImportActivitiesProviderContract::class;
    }
}
