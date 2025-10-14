<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PcPreviousStudiesTipificationHistory;
use App\Repositories\BaseRepository;

/**
 * Class PcPreviousStudiesTipificationHistoryRepository
 * @package Modules\ContractualProcess\Repositories
 * @version March 10, 2021, 4:32 pm -05
*/

class PcPreviousStudiesTipificationHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_danger',
        'danger',
        'effect',
        'probability',
        'impact',
        'allocation_danger',
        'pc_previous_studies_h_id'
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
        return PcPreviousStudiesTipificationHistory::class;
    }
}
