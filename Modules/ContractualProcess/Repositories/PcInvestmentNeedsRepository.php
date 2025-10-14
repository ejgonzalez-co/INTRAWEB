<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PcInvestmentNeeds;
use App\Repositories\BaseRepository;

/**
 * Class PcInvestmentNeedsRepository
 * @package Modules\ContractualProcess\Repositories
 * @version December 4, 2020, 5:44 pm -05
*/

class PcInvestmentNeedsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
        'estimated_value',
        'observation',
        'pc_needs_id'
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
        return PcInvestmentNeeds::class;
    }
}
