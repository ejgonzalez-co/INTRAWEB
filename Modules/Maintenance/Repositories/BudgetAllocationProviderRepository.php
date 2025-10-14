<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\BudgetAllocationProvider;
use App\Repositories\BaseRepository;

/**
 * Class BudgetAllocationProviderRepository
 * @package Modules\Maintenance\Repositories
 * @version May 28, 2021, 3:51 pm -05
*/

class BudgetAllocationProviderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'process',
        'available',
        'executed',
        'dependencias_id',
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
        return BudgetAllocationProvider::class;
    }
}
