<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\NewContracts;
use App\Repositories\BaseRepository;

/**
 * Class NewContractsRepository
 * @package Modules\Maintenance\Repositories
 * @version August 23, 2023, 10:24 am -05
*/

class NewContractsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'novelty_type',
        'consecutive',
        'observation',
        'name_user',
        'value_cdp',
        'value_contract',
        'cdp_avaible',
        'users_id',
        'mant_provider_contract_id'
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
        return NewContracts::class;
    }
}
