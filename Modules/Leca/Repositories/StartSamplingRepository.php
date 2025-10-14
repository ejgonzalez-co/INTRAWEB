<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\StartSampling;
use App\Repositories\BaseRepository;

/**
 * Class StartSamplingRepository
 * @package Modules\Leca\Repositories
 * @version January 3, 2022, 11:48 am -05
*/

class StartSamplingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'user_name',
        'vehicle_arrival_time',
        'leca_outlet',
        'time_sample_completion',
        'service_agreement',
        'sample_request',
        'time',
        'name',
        'environmental_conditions',
        'potentiometer_multiparameter',
        'chlorine_residual',
        'turbidimeter',
        'another_test',
        'other_equipment',
        'chlorine_test',
        'factor',
        'residual_color',
        'media_and_DPR',
        'mean_chlorine_value',
        'DPR_chlorine_residual',
        'date_last_ph_adjustment',
        'pending',
        'asymmetry',
        'digital_thermometer',
        'which',
        'arrival_LECA',
        'observations',
        'witness',
        'initial',
        'intermediate',
        'end',
        'standard_ph',
        'chlorine_residual_target',
        'initial_pattern'
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
        return StartSampling::class;
    }
}
