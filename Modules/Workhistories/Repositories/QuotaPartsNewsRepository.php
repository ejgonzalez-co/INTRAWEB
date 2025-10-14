<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\QuotaPartsNews;
use App\Repositories\BaseRepository;

/**
 * Class QuotaPartsNewsRepository
 * @package Modules\Workhistories\Repositories
 * @version December 10, 2020, 6:56 pm -05
*/

class QuotaPartsNewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'new',
        'type_document',
        'users_name',
        'users_id',
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
        return QuotaPartsNews::class;
    }
}
