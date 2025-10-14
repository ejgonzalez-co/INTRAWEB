<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\BudgetAssignation;
use App\Repositories\BaseRepository;

/**
 * Class BudgetAssignationRepository
 * @package Modules\Maintenance\Repositories
 * @version August 12, 2021, 3:45 pm -05
*/

class BudgetAssignationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_provider_contract_id',
        'value_cdp',
        'consecutive_cdp',
        'value_contract',
        'cdp_available',
        'observation'
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
        return BudgetAssignation::class;
    }
}
