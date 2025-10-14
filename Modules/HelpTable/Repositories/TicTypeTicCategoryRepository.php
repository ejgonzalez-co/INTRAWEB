<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicTypeTicCategory;
use App\Repositories\BaseRepository;

/**
 * Class TicTypeTicCategoryRepository
 * @package Modules\HelpTable\Repositories
 * @version June 4, 2021, 2:53 pm -05
*/

class TicTypeTicCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description'
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
        return TicTypeTicCategory::class;
    }
}
