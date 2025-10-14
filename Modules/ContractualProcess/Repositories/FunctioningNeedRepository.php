<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\FunctioningNeed;
use App\Repositories\BaseRepository;

/**
 * Class FunctioningNeedRepository
 * @package Modules\ContractualProcess\Repositories
 * @version June 18, 2021, 9:11 am -05
*/

class FunctioningNeedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pc_needs_id',
        'description',
        'estimated_start_date',
        'selection_mode',
        'estimated_total_value',
        'estimated_value_current_validity',
        'additions',
        'total_value',
        'future_validity_status',
        'observation'
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
        return FunctioningNeed::class;
    }
}
