<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Tag;
use App\Repositories\BaseRepository;

/**
 * Class TagRepository
 * @package Modules\Intranet\Repositories
 * @version July 20, 2021, 1:23 am -05
*/

class TagRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'slug'
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
        return Tag::class;
    }
}
