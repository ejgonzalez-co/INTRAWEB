<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ContractNews;
use App\Repositories\BaseRepository;

/**
 * Class ContractNewsRepository
 * @package Modules\Maintenance\Repositories
 * @version August 30, 2023, 8:06 am -05
*/

class ContractNewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'mant_provider_contract_id',
        'novelty_type',
        'number_cdp',
        'contract_modify',
        'consecutive',
        'consecutive_cdp',
        'name_user',
        'date_new',
        'time_extensión',
        'date_modification',
        'date_cdp',
        'date_start_suspension',
        'date_end_suspension',
        'date_contract_term',
        'observation',
        'attachment',
        'cdp_modify',
        'value_cdp',
        'value_contract',
        'cdp_available'
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
        return ContractNews::class;
    }
}
