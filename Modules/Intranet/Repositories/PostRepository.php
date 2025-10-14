<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Post;
use App\Repositories\BaseRepository;

/**
 * Class PostRepository
 * @package Modules\Intranet\Repositories
 * @version July 20, 2021, 1:24 am -05
*/

class PostRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'title',
        'slug',
        'cover_path',
        'content',
        'online',
        'user_id',
        'visits'
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
        return Post::class;
    }
}
