<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\DownloadCategorie;
use App\Repositories\BaseRepository;

/**
 * Class DownloadCategorieRepository
 * @package Modules\Intranet\Repositories
 * @version June 21, 2021, 7:28 pm -05
*/

class DownloadCategorieRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'parent_id',
        'title',
        'alias',
        'description',
        'published',
        'checked_out',
        'checked_out_time',
        'ordering',
        'date',
        'metakey',
        'metadesc'
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
        return DownloadCategorie::class;
    }
}
