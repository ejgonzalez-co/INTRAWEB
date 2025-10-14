<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\ExternalAnnotation;
use App\Repositories\BaseRepository;

/**
 * Class ExternalAnnotationRepository
 * @packageModules\Correspondence\Repositories
 * @version March 10, 2022, 5:34 pm -05
*/

class ExternalAnnotationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'content',
        'users_name',
        'users_id',
        'correspondence_external_id'
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
        return ExternalAnnotation::class;
    }
}
