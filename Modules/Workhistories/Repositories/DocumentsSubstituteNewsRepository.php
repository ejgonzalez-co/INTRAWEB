<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\DocumentsSubstituteNews;
use App\Repositories\BaseRepository;

/**
 * Class DocumentsSubstituteNewsRepository
 * @package Modules\Workhistories\Repositories
 * @version December 9, 2020, 8:49 am -05
*/

class DocumentsSubstituteNewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'new',
        'type_document',
        'users_name',
        'users_id',
        'p_substitute_doc_id',
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
        return DocumentsSubstituteNews::class;
    }
}
