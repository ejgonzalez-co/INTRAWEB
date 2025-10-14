<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TechnicalConcept;
use App\Repositories\BaseRepository;

/**
 * Class TechnicalConceptRepository
 * @package Modules\HelpTable\Repositories
 * @version April 12, 2023, 4:48 pm -05
*/

class TechnicalConceptRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_staff_member',
        'technician_id',
        'reviewer_id',
        'approver_id',
        'equipment_type',
        'equipment_mark',
        'equipment_model',
        'equipment_serial',
        'inventory_plate',
        'inventory_manager',
        'status',
        'consecutive',
        'technical_concept',
        'observations',
        'date_issue_concept',
        'expiration_date',
        'url_attachments'
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
        return TechnicalConcept::class;
    }
}
