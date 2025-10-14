<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\FuelDocument;
use App\Repositories\BaseRepository;

/**
 * Class FuelDocumentRepository
 * @package Modules\Maintenance\Repositories
 * @version August 20, 2021, 8:50 am -05
*/

class FuelDocumentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_vehicle_fuels_id',
        'name',
        'description',
        'url_document_fuel'
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
        return FuelDocument::class;
    }
}
