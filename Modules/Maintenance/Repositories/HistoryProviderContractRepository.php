<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\HistoryProviderContract;
use App\Repositories\BaseRepository;

/**
 * Class HistoryProviderContractRepository
 * @package Modules\Maintenance\Repositories
 * @version September 14, 2021, 11:03 am -05
*/

class HistoryProviderContractRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'value_contract',
        'name_user',
        'observation',
        'cd_avaible',
        'users_id',
        'object',
        'provider'
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
        return HistoryProviderContract::class;
    }
}
