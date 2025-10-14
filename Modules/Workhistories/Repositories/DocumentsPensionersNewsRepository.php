<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\DocumentsPensionersNews;
use App\Repositories\BaseRepository;

/**
 * Class DocumentsNewsRepository
 * @package Modules\Workhistories\Repositories
 * @version October 28, 2020, 4:35 pm -05
*/

class DocumentsPensionersNewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'new',
        'type_document',
        'work_histories_documents_id',
        'work_histories_id'
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
        return DocumentsPensionersNews::class;
    }
}
