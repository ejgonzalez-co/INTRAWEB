<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\ProcessLeaders;
use App\Repositories\BaseRepository;

/**
 * Class ProcessLeadersRepository
 * @package Modules\ContractualProcess\Repositories
 * @version December 1, 2020, 9:32 am -05
*/

class ProcessLeadersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'leader_name',
        'name_process',
        'users_id'
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
        return ProcessLeaders::class;
    }
}
