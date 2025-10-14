<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\DownloadTagsSelected;
use App\Repositories\BaseRepository;

/**
 * Class DownloadTagsSelectedRepository
 * @package Modules\Intranet\Repositories
 * @version June 28, 2021, 9:49 pm -05
*/

class DownloadTagsSelectedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'intranet_downloads_tags_id',
        'intranet_downloads_id'
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
        return DownloadTagsSelected::class;
    }
}
