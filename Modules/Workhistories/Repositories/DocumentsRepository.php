<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\Documents;
use App\Repositories\BaseRepository;

/**
 * Class DocumentsRepository
 * @package Modules\Workhistories\Repositories
 * @version October 22, 2020, 2:27 pm -05
*/

class DocumentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_document',
        'description',
        'state',
        'work_histories_config_documents_id',
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
        return Documents::class;
    }
}
