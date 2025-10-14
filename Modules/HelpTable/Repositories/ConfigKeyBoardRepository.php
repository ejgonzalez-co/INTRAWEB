<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigKeyBoard;
use App\Repositories\BaseRepository;

/**
 * Class ConfigKeyBoardRepository
 * @package Modules\HelpTable\Repositories
 * @version January 23, 2023, 8:24 am -05
*/

class ConfigKeyBoardRepository extends BaseRepository
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
        return ConfigKeyBoard::class;
    }
}
