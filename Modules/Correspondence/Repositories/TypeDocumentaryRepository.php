<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\TypeDocumentary;
use App\Repositories\BaseRepository;

/**
 * Class TypeDocumentaryRepository
 * @packageModules\Correspondence\Repositories
 * @version January 20, 2022, 12:30 am -05
*/

class TypeDocumentaryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'state'
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
        return TypeDocumentary::class;
    }
}
