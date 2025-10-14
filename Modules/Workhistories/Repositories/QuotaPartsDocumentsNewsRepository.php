<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\QuotaPartsDocumentsNews;
use App\Repositories\BaseRepository;

/**
 * Class QuotaPartsDocumentsNewsRepository
 * @package Modules\Workhistories\Repositories
 * @version December 10, 2020, 6:06 pm -05
*/

class QuotaPartsDocumentsNewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'new',
        'type_document',
        'users_name',
        'users_id',
        'cp_p_documents_id',
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
        return QuotaPartsDocumentsNews::class;
    }
}
