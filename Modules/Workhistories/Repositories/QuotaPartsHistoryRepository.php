<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\QuotaPartsHistory;
use App\Repositories\BaseRepository;

/**
 * Class QuotaPartsHistoryRepository
 * @package Modules\Workhistories\Repositories
 * @version December 10, 2020, 6:55 pm -05
*/

class QuotaPartsHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_company',
        'time_work',
        'observation',
        'url_document',
        'cp_pensionados_id',
        'work_histories_cp_id'
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
        return QuotaPartsHistory::class;
    }
}
