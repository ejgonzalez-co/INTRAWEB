<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\RequestAnnotation;
use App\Repositories\BaseRepository;

/**
 * Class RequestAnnotationRepository
 * @package Modules\Maintenance\Repositories
 * @version January 11, 2024, 2:29 pm -05
*/

class RequestAnnotationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'mant_sn_request_id',
        'anotacion',
        'name_user'
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
        return RequestAnnotation::class;
    }
}
