<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\DownloadsTags;
use App\Repositories\BaseRepository;

/**
 * Class DownloadsTagsRepository
 * @package Modules\Intranet\Repositories
 * @version June 27, 2021, 9:55 am -05
*/

class DownloadsTagsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'alias',
        'link_ext',
        'description',
        'published',
        'checked_out',
        'checked_out_time',
        'ordering',
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
        return DownloadsTags::class;
    }
}
