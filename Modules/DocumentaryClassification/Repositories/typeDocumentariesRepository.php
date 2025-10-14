<?php

namespace Modules\DocumentaryClassification\Repositories;

use Modules\DocumentaryClassification\Models\typeDocumentaries;
use App\Repositories\BaseRepository;

/**
 * Class typeDocumentariesRepository
 * @package Modules\DocumentaryClassification\Repositories
 * @version March 31, 2023, 3:02 am -05
*/

class typeDocumentariesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description'
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
        return typeDocumentaries::class;
    }
}
