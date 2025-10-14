<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\TireQuantitites;
use App\Repositories\BaseRepository;

/**
 * Class TireQuantititesRepository
 * @package Modules\Maintenance\Repositories
 * @version September 4, 2021, 11:59 am -05
*/

class TireQuantititesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_resume_machinery_vehicles_yellow_id',
        'dependencias_id',
        'plaque',
        'tire_quantity'
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
        return TireQuantitites::class;
    }
}
