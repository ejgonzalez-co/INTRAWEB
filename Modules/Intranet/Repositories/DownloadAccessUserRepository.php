<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\DownloadAccessUser;
use App\Repositories\BaseRepository;

/**
 * Class DownloadAccessUserRepository
 * @package Modules\Intranet\Repositories
 * @version June 24, 2021, 8:18 pm -05
*/

class DownloadAccessUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
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
        return DownloadAccessUser::class;
    }
}
