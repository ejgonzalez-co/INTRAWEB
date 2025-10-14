<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\SampleTaking;
use App\Repositories\BaseRepository;

/**
 * Class SampleTakingRepository
 * @package Modules\Leca\Repositories
 * @version January 4, 2022, 10:25 am -05
*/

class SampleTakingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_start_sampling_id',
        'lc_sample_points_id',
        'users_id',
        'user_name',
        'sample_reception_code',
        'address',
        'type_water',
        'humidity',
        'temperature',
        'hour_from_to',
        'prevailing_climatic_characteristics',
        'test_perform',
        'container_number',
        'hour',
        'according',
        'sample_characteristics',
        'observations',
        'refrigeration',
        'filtered_sample',
        'hno3',
        'h2so4',
        'hci',
        'naoh',
        'acetate',
        'ascorbic_acid',
        'charge',
        'process'
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
        return SampleTaking::class;
    }
}
