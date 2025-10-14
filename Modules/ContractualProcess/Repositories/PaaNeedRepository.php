<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PaaNeed;
use App\Repositories\BaseRepository;

/**
 * Class PaaNeedRepository
 * @package Modules\ContractualProcess\Repositories
 * @version August 4, 2021, 3:01 pm -05
*/

class PaaNeedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pc_paa_calls_id',
        'pc_process_leaders_id',
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
        return PaaNeed::class;
    }
}
