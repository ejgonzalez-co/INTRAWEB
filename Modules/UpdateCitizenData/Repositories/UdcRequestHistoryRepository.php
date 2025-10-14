<?php

namespace Modules\UpdateCitizenData\Repositories;

use Modules\UpdateCitizenData\Models\UdcRequestHistory;
use App\Repositories\BaseRepository;

/**
 * Class UdcRequestHistoryRepository
 * @package Modules\UpdateCitizenData\Repositories
 * @version April 30, 2021, 5:42 pm -05
*/

class UdcRequestHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'payment_account_number',
        'subscriber_quality',
        'citizen_name',
        'document_type',
        'identification',
        'gender',
        'telephone',
        'email',
        'date_birth',
        'users_name',
        'state',
        'udc_request_id'
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
        return UdcRequestHistory::class;
    }
}
