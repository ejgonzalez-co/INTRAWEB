<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\DocumentsProviderContract;
use App\Repositories\BaseRepository;

/**
 * Class DocumentsProviderContractRepository
 * @package Modules\Maintenance\Repositories
 * @version June 3, 2021, 10:04 am -05
*/

class DocumentsProviderContractRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'url_document',
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
        return DocumentsProviderContract::class;
    }
}
