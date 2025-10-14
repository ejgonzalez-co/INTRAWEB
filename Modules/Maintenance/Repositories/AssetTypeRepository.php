<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\AssetType;
use App\Repositories\BaseRepository;

/**
 * Class AssetTypeRepository
 * @package Modules\Maintenance\Repositories
 * @version January 21, 2021, 2:29 pm -05
*/

class AssetTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'form_type',
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
        return AssetType::class;
    }
}
