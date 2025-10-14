<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\OilElementWearConfiguration;
use App\Repositories\BaseRepository;

/**
 * Class OilElementWearConfigurationRepository
 * @package Modules\Maintenance\Repositories
 * @version December 17, 2021, 8:40 am -05
*/

class OilElementWearConfigurationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'register_date',
        'element_name',
        'rank_higher',
        'rank_lower',
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
        return OilElementWearConfiguration::class;
    }
}
