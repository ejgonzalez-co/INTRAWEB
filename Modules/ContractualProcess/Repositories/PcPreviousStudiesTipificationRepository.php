<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PcPreviousStudiesTipification;
use App\Repositories\BaseRepository;

/**
 * Class PcPreviousStudiesTipificationRepository
 * @package Modules\ContractualProcess\Repositories
 * @version January 9, 2021, 11:48 am -05
*/

class PcPreviousStudiesTipificationRepository extends BaseRepository
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
        'pc_previous_studies_id'
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
        return PcPreviousStudiesTipification::class;
    }
}
