<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ImportSparePartsProviderContract;
use App\Repositories\BaseRepository;

/**
 * Class ImportSparePartsProviderContractRepository
 * @package Modules\Maintenance\Repositories
 * @version May 28, 2021, 3:45 pm -05
*/

class ImportSparePartsProviderContractRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
        'unit_measure',
        'unit_value',
        'iva',
        'total_value',
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
        return ImportSparePartsProviderContract::class;
    }
}
