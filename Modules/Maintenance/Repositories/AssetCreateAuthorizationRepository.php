<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\AssetCreateAuthorization;
use App\Repositories\BaseRepository;

/**
 * Class AssetCreateAuthorizationRepository
 * @package Modules\Maintenance\Repositories
 * @version February 1, 2021, 11:03 am -05
*/

class AssetCreateAuthorizationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'responsable',
        'dependencias_id'
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
        return AssetCreateAuthorization::class;
    }
}
