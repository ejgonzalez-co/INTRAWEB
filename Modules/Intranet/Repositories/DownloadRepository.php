<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Download;
use App\Repositories\BaseRepository;

/**
 * Class DownloadRepository
 * @package Modules\Intranet\Repositories
 * @version June 28, 2021, 7:42 am -05
*/

class DownloadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'owner_id',
        'title',
        'alias',
        'filename',
        'filesize',
        'filename_play',
        'filename_preview',
        'author',
        'author_email',
        'author_url',
        'license',
        'license_url',
        'video_filename',
        'image_filename',
        'image_filename_spec1',
        'image_filename_spec2',
        'image_download',
        'link_external',
        'mirror1link',
        'mirror1title',
        'mirror1target',
        'mirror2link',
        'mirror2title',
        'mirror2target',
        'description',
        'features',
        'changelog',
        'notes',
        'version',
        'directlink',
        'date',
        'publish_up',
        'publish_down',
        'hits',
        'published',
        'approved',
        'checked_out',
        'checked_out_time',
        'ordering',
        'unaccessible_file',
        'metakey',
        'metadesc',
        'intranet_downloads_categories_id',
        'intranet_downloads_licenses_id',
        'users_id'
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
        return Download::class;
    }
}
