<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PcPreviousStudiesDocuments;
use App\Repositories\BaseRepository;

/**
 * Class PcPreviousStudiesDocumentsRepository
 * @package Modules\ContractualProcess\Repositories
 * @version January 14, 2021, 2:19 pm -05
*/

class PcPreviousStudiesDocumentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_document',
        'description',
        'state',
        'url_document',
        'sheet',
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
        return PcPreviousStudiesDocuments::class;
    }
}
