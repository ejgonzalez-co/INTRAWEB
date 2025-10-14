<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigTower;
use App\Repositories\BaseRepository;

/**
 * Class ConfigTowerRepository
 * @package Modules\HelpTable\Repositories
 * @version January 21, 2023, 5:25 pm -05
*/

class ConfigTowerRepository extends BaseRepository
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
        return ConfigTower::class;
    }
}
