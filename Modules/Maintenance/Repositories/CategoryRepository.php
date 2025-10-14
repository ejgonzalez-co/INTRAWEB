<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\Category;
use App\Repositories\BaseRepository;

/**
 * Class CategoryRepository
 * @package Modules\Maintenance\Repositories
 * @version January 22, 2021, 12:04 pm -05
*/

class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'mant_asset_type_id'
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
