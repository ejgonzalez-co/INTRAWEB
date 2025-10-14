<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ButgetExecution;
use App\Repositories\BaseRepository;

/**
 * Class ButgetExecutionRepository
 * @package Modules\Maintenance\Repositories
 * @version August 12, 2021, 3:45 pm -05
*/

class ButgetExecutionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_administration_cost_items_id',
        'minutes',
        'date',
        'executed_value',
        'new_value_available',
        'percentage_execution_item'
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
        return ButgetExecution::class;
    }
}
