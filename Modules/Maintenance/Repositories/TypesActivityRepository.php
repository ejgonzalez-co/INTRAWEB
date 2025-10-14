<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\TypesActivity;
use App\Repositories\BaseRepository;

/**
 * Class TypesActivityRepository
 * @package Modules\Maintenance\Repositories
 * @version March 12, 2021, 9:39 am -05
*/

class TypesActivityRepository extends BaseRepository
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
        return TypesActivity::class;
    }
}
