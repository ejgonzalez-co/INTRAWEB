<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\ContentManagement;
use App\Repositories\BaseRepository;

/**
 * Class ContentManagementRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 26, 2023, 3:23 pm -05
*/

class ContentManagementRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'archive',
        'color'
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
        return ContentManagement::class;
    }
}
