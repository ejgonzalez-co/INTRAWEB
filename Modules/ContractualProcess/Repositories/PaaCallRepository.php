<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PaaCall;
use App\Repositories\BaseRepository;

/**
 * Class PaaCallRepository
 * @package Modules\ContractualProcess\Repositories
 * @version August 3, 2021, 3:49 pm -05
*/

class PaaCallRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'validity',
        'name',
        'start_date',
        'closing_alert_date',
        'closing_date',
        'attached',
        'observation_message',
        'state'
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
        return PaaCall::class;
    }
}
