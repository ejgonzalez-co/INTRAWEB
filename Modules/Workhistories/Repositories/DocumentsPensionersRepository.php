<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\DocumentsPensioners;
use App\Repositories\BaseRepository;

/**
 * Class DocumentsPensionersRepository
 * @package Modules\Workhistories\Repositories
 * @version December 4, 2020, 3:18 pm -05
*/

class DocumentsPensionersRepository extends BaseRepository
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
        'work_histories_p_id',
        'config_documents_id'
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
        return DocumentsPensioners::class;
    }
}
