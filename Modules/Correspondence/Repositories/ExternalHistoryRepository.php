<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\ExternalHistory;
use App\Repositories\BaseRepository;

/**
 * Class ExternalHistoryRepository
 * @packageModules\Correspondence\Repositories
 * @version March 9, 2022, 5:51 pm -05
*/

class ExternalHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'consecutive',
        'consecutive_order',
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
        'editor',
        'origen',
        'recipients',
        'document',
        'document_pdf',
        'from',
        'from_id',
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
        'year',
        'external_all',
        'type',
        'users_id',
        'dependencias_id',
        'correspondence_external_id',
        'annexes_digital',
        'hash_document_pdf',
        'validation_code',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'external_received_consecutive'
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
        return ExternalHistory::class;
    }
}
