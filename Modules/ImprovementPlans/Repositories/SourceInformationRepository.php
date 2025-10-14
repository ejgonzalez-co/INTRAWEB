<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\SourceInformation;
use App\Repositories\BaseRepository;

/**
 * Class SourceInformationRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 25, 2023, 5:08 pm -05
*/

class SourceInformationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
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
        return SourceInformation::class;
    }
}
