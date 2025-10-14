<?php

namespace Modules\DocumentaryClassification\Repositories;

use Modules\DocumentaryClassification\Models\dependenciasSerieSubseries;
use App\Repositories\BaseRepository;

/**
 * Class dependenciasSerieSubseriesRepository
 * @package Modules\DocumentaryClassification\Repositories
 * @version April 16, 2023, 8:37 pm -05
*/

class dependenciasSerieSubseriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_dependencia',
        'id_series_subseries'
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
        return dependenciasSerieSubseries::class;
    }
}
