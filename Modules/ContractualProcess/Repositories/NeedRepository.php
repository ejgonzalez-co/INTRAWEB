<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\Need;
use App\Repositories\BaseRepository;

/**
 * Class NeedRepository
 * @package Modules\ContractualProcess\Repositories
 * @version June 18, 2021, 8:50 am -05
*/

class NeedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pc_paa_calls_id',
        'pc_process_leaders_id',
        'assigned_user_id',
        'name_process',
        'state',
        'total_value_paa',
        'total_operating_value',
        'future_validity_status',
        'total_investment_value'
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
        return Need::class;
    }
}
