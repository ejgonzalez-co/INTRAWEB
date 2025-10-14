<?php

namespace Modules\UpdateCitizenData\Repositories;

use Modules\UpdateCitizenData\Models\UdcRequest;
use App\Repositories\BaseRepository;

/**
 * Class UdcRequestRepository
 * @package Modules\UpdateCitizenData\Repositories
 * @version April 30, 2021, 5:39 pm -05
*/

class UdcRequestRepository extends BaseRepository
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
        'state'
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
        return UdcRequest::class;
    }
}
