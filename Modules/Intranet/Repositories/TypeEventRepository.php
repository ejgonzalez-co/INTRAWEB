<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\TypeEvent;
use App\Repositories\BaseRepository;

/**
 * Class TypeEventRepository
 * @package Modules\Intranet\Repositories
 * @version June 28, 2021, 1:55 am -05
*/

class TypeEventRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
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
        return TypeEvent::class;
    }
}
