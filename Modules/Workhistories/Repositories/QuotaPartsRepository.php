<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\QuotaParts;
use App\Repositories\BaseRepository;

/**
 * Class QuotaPartsRepository
 * @package Modules\Workhistories\Repositories
 * @version December 10, 2020, 5:26 pm -05
*/

class QuotaPartsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_company',
        'time_work',
        'observation',
        'url_document',
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
        return QuotaParts::class;
    }
}
