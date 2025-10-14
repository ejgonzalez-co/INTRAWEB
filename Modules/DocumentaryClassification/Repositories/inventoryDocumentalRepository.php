<?php

namespace Modules\DocumentaryClassification\Repositories;

use Modules\DocumentaryClassification\Models\inventoryDocumental;
use App\Repositories\BaseRepository;

/**
 * Class inventoryDocumentalRepository
 * @package Modules\DocumentaryClassification\Repositories
 * @version March 31, 2023, 3:07 am -05
*/

class inventoryDocumentalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_dependencias',
        'id_series_subseries',
        'description_expedient',
        'folios',
        'clasification',
        'consultation_frequency',
        'soport',
        'shelving',
        'tray',
        'box',
        'file',
        'book',
        'date_initial',
        'date_finish',
        'range_initial',
        'range_finish',
        'observation'
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
        return inventoryDocumental::class;
    }
}
