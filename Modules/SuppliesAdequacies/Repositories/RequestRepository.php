<?php

namespace Modules\SuppliesAdequacies\Repositories;

use Modules\SuppliesAdequacies\Models\RequestSuppliesAdequacies;
use App\Repositories\BaseRepository;

/**
 * Class RequestRepository
 * @package Modules\SuppliesAdequacies\Repositories
 * @version November 12, 2024, 8:07 am -05
*/

class RequestRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_creator_id',
        'assigned_officer_id',
        'consecutive',
        'subject',
        'need_type',
        'justification',
        'url_documents',
        'status',
        'requirement_type',
        'term_type',
        'quantity_term',
        'cost_center',
        'supplier_verification',
        'supplier_name',
        'tracking',
        'expiration_date',
        'date_attention',
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
        return RequestSuppliesAdequacies::class;
    }
}
