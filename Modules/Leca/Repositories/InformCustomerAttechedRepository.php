<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\InformCustomerAtteched;
use App\Repositories\BaseRepository;

/**
 * Class InformCustomerAttechedRepository
 * @package Modules\leca\Repositories
 * @version March 3, 2023, 9:03 am -05
*/

class InformCustomerAttechedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_rm_report_management_id',
        'users_id',
        'name',
        'user_name',
        'attachment',
        'status',
        'comments'
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
        return InformCustomerAtteched::class;
    }
}
