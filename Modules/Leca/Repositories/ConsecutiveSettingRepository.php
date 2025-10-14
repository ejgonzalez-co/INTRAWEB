<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ConsecutiveSetting;
use App\Repositories\BaseRepository;

/**
 * Class ConsecutiveSettingRepository
 * @package Modules\leca\Repositories
 * @version December 13, 2022, 9:57 am -05
*/

class ConsecutiveSettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_sample_taking_id',
        'users_id',
        'lc_customers_id',
        'consecutive',
        'nex_consecutiveIC',
        'name_customer',
        'coments_consecutive',
        'date_report',
        'user_name',
        'nex_consecutiveIE',
        'status',
        'query_report'
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
        return ConsecutiveSetting::class;
    }
}
