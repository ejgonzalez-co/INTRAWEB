<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigMonitor;
use App\Repositories\BaseRepository;

/**
 * Class ConfigMonitorRepository
 * @package Modules\HelpTable\Repositories
 * @version January 23, 2023, 11:31 am -05
*/

class ConfigMonitorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'brand_name',
        'status'
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
        return ConfigMonitor::class;
    }
}
