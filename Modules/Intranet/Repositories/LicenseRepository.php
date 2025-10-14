<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\License;
use App\Repositories\BaseRepository;

/**
 * Class LicenseRepository
 * @package Modules\Intranet\Repositories
 * @version June 18, 2021, 6:31 pm -05
*/

class LicenseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'alias',
        'description',
        'checked_out',
        'checked_out_time',
        'published',
        'ordering'
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
        return License::class;
    }
}
