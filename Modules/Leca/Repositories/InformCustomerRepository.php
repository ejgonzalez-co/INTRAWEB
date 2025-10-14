<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\InformCustomer;
use App\Repositories\BaseRepository;

/**
 * Class InformCustomerRepository
 * @package Modules\leca\Repositories
 * @version March 2, 2023, 11:39 am -05
*/

class InformCustomerRepository extends BaseRepository
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
        'nex_consecutiveIE',
        'name_customer',
        'mail_customer',
        'coments_consecutive',
        'date_report',
        'user_name',
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
        return InformCustomer::class;
    }
}
