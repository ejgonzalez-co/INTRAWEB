<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PcPreviousStudiesRadication;
use App\Repositories\BaseRepository;

/**
 * Class PcPreviousStudiesRadicationRepository
 * @package Modules\ContractualProcess\Repositories
 * @version January 24, 2021, 9:30 pm -05
*/

class PcPreviousStudiesRadicationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'process',
        'object',
        'boss',
        'value',
        'date_send',
        'notification',
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
        return PcPreviousStudiesRadication::class;
    }
}
