<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ReportManagement;
use App\Repositories\BaseRepository;

/**
 * Class ReportManagementRepository
 * @package Modules\Leca\Repositories
 * @version December 7, 2022, 2:19 pm -05
*/

class ReportManagementRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_sample_taking_id',
        'users_id',
        'lc_customers_id',
        'user_name',
        'consecutive',
        'status',
        'coments_consecutive'
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
        return ReportManagement::class;
    }
}
