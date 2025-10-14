<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\SetTire;
use App\Repositories\BaseRepository;

/**
 * Class SetTireRepository
 * @package Modules\Maintenance\Repositories
 * @version August 28, 2021, 10:38 am -05
*/

class SetTireRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_tire_brand_id',
        'registration_date',
        'tire_reference',
        'maximum_wear',
        'observation'
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
        return SetTire::class;
    }
}
