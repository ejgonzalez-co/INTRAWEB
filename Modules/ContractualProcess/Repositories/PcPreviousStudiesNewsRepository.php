<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PcPreviousStudiesNews;
use App\Repositories\BaseRepository;

/**
 * Class PcPreviousStudiesNewsRepository
 * @package Modules\ContractualProcess\Repositories
 * @version January 15, 2021, 4:17 pm -05
*/

class PcPreviousStudiesNewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_name',
        'state',
        'observation',
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
        return PcPreviousStudiesNews::class;
    }
}
