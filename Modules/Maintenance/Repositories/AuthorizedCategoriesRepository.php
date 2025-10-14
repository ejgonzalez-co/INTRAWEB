<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\AuthorizedCategories;
use App\Repositories\BaseRepository;

/**
 * Class AuthorizedCategoriesRepository
 * @package Modules\Maintenance\Repositories
 * @version February 3, 2021, 3:28 pm -05
*/

class AuthorizedCategoriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'asset_authorization_id',
        'mant_asset_type_id',
        'mant_category_id'
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
        return AuthorizedCategories::class;
    }
}
