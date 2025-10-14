<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\EquipmentResumeDocument;
use App\Repositories\BaseRepository;

/**
 * Class EquipmentResumeDocumentRepository
 * @package Modules\HelpTable\Repositories
 * @version January 24, 2023, 4:19 pm -05
*/

class EquipmentResumeDocumentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ht_tic_equipment_resume_id',
        'name',
        'description',
        'url'
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
        return EquipmentResumeDocument::class;
    }
}
