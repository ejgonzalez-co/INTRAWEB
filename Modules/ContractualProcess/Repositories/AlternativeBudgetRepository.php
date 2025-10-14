<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\AlternativeBudget;
use App\Repositories\BaseRepository;

/**
 * Class AlternativeBudgetRepository
 * @package Modules\ContractualProcess\Repositories
 * @version January 8, 2021, 3:38 pm -05
*/

class AlternativeBudgetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pc_investment_technical_sheets_id',
        'total_direct_cost',
        'total_direct_aqueduct',
        'total_direct_percentage_aqueduct',
        'total_direct_sewerage',
        'total_direct_percentage_sewerage',
        'total_direct_cleanliness',
        'total_direct_percentage_cleanliness',
        'total_project_cost',
        'total_project_aqueduct',
        'total_project_percentage_aqueduct',
        'total_project_sewerage',
        'total_project_percentage_sewerage',
        'total_project_cleanliness',
        'total_project_percentage_cleanliness'
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
        return AlternativeBudget::class;
    }
}
