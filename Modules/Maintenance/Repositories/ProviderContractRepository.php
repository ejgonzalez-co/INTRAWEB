<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ProviderContract;
use App\Repositories\BaseRepository;

/**
 * Class ProviderContractRepository
 * @package Modules\Maintenance\Repositories
 * @version May 28, 2021, 3:44 pm -05
*/

class ProviderContractRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'object',
        'type_contract',
        'contract_number',
        'start_date',
        'CDP_approved',
        'CDP_available',
        'contract_value',
        'closing_date',
        'last_minute',
        'executed_value',
        'balance_value',
        'execution_percentage',
        'mant_providers_id'
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
        return ProviderContract::class;
    }
}
