<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Category;
use App\Repositories\BaseRepository;

/**
 * Class CategoryRepository
 * @package Modules\Intranet\Repositories
 * @version August 31, 2021, 2:56 pm -05
*/

class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return Category::class;
    }
}
