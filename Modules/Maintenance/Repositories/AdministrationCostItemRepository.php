<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\AdministrationCostItem;
use App\Repositories\BaseRepository;

/**
 * Class AdministrationCostItemRepository
 * @package Modules\Maintenance\Repositories
 * @version August 12, 2021, 3:45 pm -05
*/

class AdministrationCostItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code_cost',
        'cost_center_name',
        'cost_center',
        'value_item',
        'observation',
        'mant_heading_id',
        'mant_center_cost_id',
        'mant_budget_assignation_id'
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
        return AdministrationCostItem::class;
    }
}
