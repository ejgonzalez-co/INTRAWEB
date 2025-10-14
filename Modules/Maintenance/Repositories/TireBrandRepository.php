<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\TireBrand;
use App\Repositories\BaseRepository;

/**
 * Class TireBrandRepository
 * @package Modules\Maintenance\Repositories
 * @version August 27, 2021, 2:55 pm -05
*/

class TireBrandRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'brand_name',
        'descripction'
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
        return TireBrand::class;
    }
}
