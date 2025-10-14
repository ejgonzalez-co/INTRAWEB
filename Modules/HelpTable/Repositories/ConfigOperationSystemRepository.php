<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigOperationSystem;
use App\Repositories\BaseRepository;

/**
 * Class ConfigOperationSystemRepository
 * @package Modules\HelpTable\Repositories
 * @version February 28, 2023, 10:36 am -05
*/

class ConfigOperationSystemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
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
        return ConfigOperationSystem::class;
    }
}
