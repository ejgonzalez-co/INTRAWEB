<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\Oil;
use App\Repositories\BaseRepository;

/**
 * Class OilRepository
 * @package Modules\Maintenance\Repositories
 * @version December 17, 2021, 11:17 am -05
*/

class OilRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_oil_element_wear_configurations_id',
        'register_date',
        'show_type',
        'component',
        'serial_number',
        'brand',
        'model',
        'job_place',
        'number_warranty_extended',
        'work_order',
        'serial_component',
        'model_component',
        'maker_component',
        'number_control_lab',
        'grade_oil',
        'type_fluid',
        'date_finished'
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
        return Oil::class;
    }
}
