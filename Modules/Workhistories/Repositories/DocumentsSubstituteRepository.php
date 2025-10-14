<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\DocumentsSubstitute;
use App\Repositories\BaseRepository;

/**
 * Class DocumentsSubstituteRepository
 * @package Modules\Workhistories\Repositories
 * @version December 7, 2020, 11:07 am -05
*/

class DocumentsSubstituteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_document',
        'description',
        'state',
        'url_document',
        'sheet',
        'config_documents_id',
        'work_histories_p_substitute_id'
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
        return DocumentsSubstitute::class;
    }
}
