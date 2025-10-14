<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\InternalAnnotation;
use App\Repositories\BaseRepository;

/**
 * Class InternalAnnotationRepository
 * @packageModules\Correspondence\Repositories
 * @version March 10, 2022, 5:34 pm -05
*/

class InternalAnnotationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'content',
        'users_name',
        'correspondence_internal_id',
        'users_id'
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
        return InternalAnnotation::class;
    }
}
