<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\SupportsProvider;
use App\Repositories\BaseRepository;

/**
 * Class SupportsProviderRepository
 * @package Modules\Maintenance\Repositories
 * @version February 22, 2021, 3:22 pm -05
*/

class SupportsProviderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'url_document',
        'mant_providers_id'
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
        return SupportsProvider::class;
    }
}
