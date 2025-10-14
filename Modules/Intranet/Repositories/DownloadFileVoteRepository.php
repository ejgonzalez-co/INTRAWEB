<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\DownloadFileVote;
use App\Repositories\BaseRepository;

/**
 * Class DownloadFileVoteRepository
 * @package Modules\Intranet\Repositories
 * @version July 2, 2021, 11:35 am -05
*/

class DownloadFileVoteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rating',
        'ordering',
        'intranet_downloads_id',
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
        return DownloadFileVote::class;
    }
}
