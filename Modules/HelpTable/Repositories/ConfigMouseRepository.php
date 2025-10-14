<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigMouse;
use App\Repositories\BaseRepository;

/**
 * Class ConfigMouseRepository
 * @package Modules\HelpTable\Repositories
 * @version January 23, 2023, 9:19 am -05
*/

class ConfigMouseRepository extends BaseRepository
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
        return ConfigMouse::class;
    }
}
