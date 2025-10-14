<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\ConfigurationDocuments;
use App\Repositories\BaseRepository;

/**
 * Class ConfigurationDocumentsRepository
 * @package Modules\Workhistories\Repositories
 * @version October 9, 2020, 3:51 pm -05
*/

class ConfigurationDocumentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'state'
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
        return ConfigurationDocuments::class;
    }
}
