<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\QuotaPartsDocPensioners;
use App\Repositories\BaseRepository;

/**
 * Class QuotaPartsDocPensionersRepository
 * @package Modules\Workhistories\Repositories
 * @version December 10, 2020, 5:08 pm -05
*/

class QuotaPartsDocPensionersRepository extends BaseRepository
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
        'config_documents_id',
        'cp_pensionados_id'
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
        return QuotaPartsDocPensioners::class;
    }
}
