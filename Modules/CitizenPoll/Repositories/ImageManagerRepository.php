<?php

namespace Modules\CitizenPoll\Repositories;

use Modules\CitizenPoll\Models\ImageManager;
use App\Repositories\BaseRepository;

/**
 * Class ImageManagerRepository
 * @package Modules\CitizenPoll\Repositories
 * @version December 16, 2021, 2:28 pm -05
*/

class ImageManagerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'url_image'
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
        return ImageManager::class;
    }
}
