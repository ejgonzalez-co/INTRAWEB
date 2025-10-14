<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\TechnicalSheets;
use App\Repositories\BaseRepository;

/**
 * Class TechnicalSheetsRepository
 * @package Modules\ContractualProcess\Repositories
 * @version January 8, 2021, 8:34 am -05
*/

class TechnicalSheetsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'dependencias_id',
        'pc_management_unit_id',
        'cities_id',
        'code_bppiepa',
        'validity',
        'date_presentation',
        'update_date',
        'project_name',
        'responsible_user',
        'municipal_development_plan',
        'period',
        'strategic_line',
        'program',
        'subprogram',
        'sector',
        'identification_project',
        'description_problem_need',
        'project_description',
        'justification',
        'background',
        'service_coverage',
        'number_inhabitants',
        'neighborhood',
        'commune'
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
        return TechnicalSheets::class;
    }
}
