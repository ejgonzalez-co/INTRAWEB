<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\Internal;
use App\Repositories\BaseRepository;

/**
 * Class InternalRepository
 * @packageModules\Correspondence\Repositories
 * @version January 19, 2022, 3:53 pm -05
*/

class InternalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'consecutive',
        'state',
        'title',
        'content',
        'folios',
        'annexes',
        'annexes_description',
        'type_document',
        'require_answer',
        'answer_consecutive',
        'template',
        'template_preview',
        'editor',
        'origen',
        'destination',
        'document',
        'document_pdf',
        'from',
        'dependency_from',
        'elaborated',
        'reviewd',
        'approved',
        'elaborated_names',
        'reviewd_names',
        'approved_names',
        'creator_name',
        'creator_dependency_name',
        'elaborated_now',
        'reviewd_now',
        'approved_now',
        'number_review',
        'observation',
        'times_read',
        'user_from_last_update',
        'user_for_last_update',
        'type',
        'users_id',
        'dependencias_id',
        'annexes_digital',
        'hash_document_pdf',
        'validation_code',
        'classification_serie',
        'classification_subserie',
        'classification_production_office'
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
        return Internal::class;
    }
}
