<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\TypeImprovementPlan;
use App\Repositories\BaseRepository;

/**
 * Class TypeImprovementPlanRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 31, 2023, 8:48 am -05
*/

class TypeImprovementPlanRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'code',
        'name',
        'status',
        'days_anticipated',
        'message'
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
        return TypeImprovementPlan::class;
    }
}
