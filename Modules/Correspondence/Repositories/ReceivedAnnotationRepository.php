<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\ReceivedAnnotation;
use App\Repositories\BaseRepository;

/**
 * Class ReceivedAnnotationRepository
 * @packageModules\Correspondence\Repositories
 * @version April 25, 2022, 4:19 am -05
*/

class ReceivedAnnotationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'external_received_id',
        'users_id',
        'users_name',
        'annotation'
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
        return ReceivedAnnotation::class;
    }
}
