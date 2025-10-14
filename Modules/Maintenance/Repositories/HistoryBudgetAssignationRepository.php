<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\HistoryBudgetAssignation;
use App\Repositories\BaseRepository;

/**
 * Class HistoryBudgetAssignationRepository
 * @package Modules\Maintenance\Repositories
 * @version September 13, 2021, 4:47 pm -05
*/

class HistoryBudgetAssignationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'observation',
        'name_user',
        'value_cdp',
        'value_contract',
        'cdp_avaible',
        'users_id',
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
        return HistoryBudgetAssignation::class;
    }
}
