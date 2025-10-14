<?php

namespace Modules\DocumentaryClassification\Repositories;

use Modules\DocumentaryClassification\Models\documentarySerieSubseries;
use App\Repositories\BaseRepository;

/**
 * Class documentarySerieSubseriesRepository
 * @package Modules\DocumentaryClassification\Repositories
 * @version April 9, 2023, 4:43 pm -05
*/

class documentarySerieSubseriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_type_documentaries',
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
        return documentarySerieSubseries::class;
    }
}
