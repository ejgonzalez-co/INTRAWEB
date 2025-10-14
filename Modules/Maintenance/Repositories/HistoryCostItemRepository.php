<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\HistoryCostItem;
use App\Repositories\BaseRepository;

/**
 * Class HistoryCostItemRepository
 * @package Modules\Maintenance\Repositories
 * @version September 13, 2021, 11:52 am -05
*/

class HistoryCostItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'name_cost',
        'observation',
        'code_cost',
        'cost_center',
        'cost_center_name',
        'value_item',
        'name_user',
        'users_id',
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
        return HistoryCostItem::class;
    }
}
