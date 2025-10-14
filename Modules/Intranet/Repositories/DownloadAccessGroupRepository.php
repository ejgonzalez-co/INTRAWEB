<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\DownloadAccessGroup;
use App\Repositories\BaseRepository;

/**
 * Class DownloadAccessGroupRepository
 * @package Modules\Intranet\Repositories
 * @version June 21, 2021, 7:28 pm -05
*/

class DownloadAccessGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'group_id',
        'intranet_downloads_categories_id'
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
        return DownloadAccessGroup::class;
    }
}
