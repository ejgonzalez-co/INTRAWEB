<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\TypeImprovementOpportunity;
use App\Repositories\BaseRepository;

/**
 * Class TypeImprovementOpportunityRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 26, 2023, 2:49 pm -05
*/

class TypeImprovementOpportunityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name',
        'status'
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
        return TypeImprovementOpportunity::class;
    }
}
