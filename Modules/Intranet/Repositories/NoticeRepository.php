<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Notice;
use App\Repositories\BaseRepository;

/**
 * Class NoticeRepository
 * @package Modules\Intranet\Repositories
 * @version August 31, 2021, 1:30 pm -05
*/

class NoticeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'content',
        'users_name',
        'state',
        'date_start',
        'date_end',
        'views',
        'image_banner',
        'image_presentation',
        'annexes',
        'keywords',
        'featured',
        'users_id',
        'intranet_news_category_id'
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
        return Notice::class;
    }
}
