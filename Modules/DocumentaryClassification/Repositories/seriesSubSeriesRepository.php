<?php

namespace Modules\DocumentaryClassification\Repositories;

use Modules\DocumentaryClassification\Models\seriesSubSeries;
use App\Repositories\BaseRepository;

/**
 * Class seriesSubSeriesRepository
 * @package Modules\DocumentaryClassification\Repositories
 * @version March 31, 2023, 3:04 am -05
*/

class seriesSubSeriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'no_serie',
        'name_serie',
        'no_subserie',
        'name_subserie',
        'time_gestion_archives',
        'time_central_file',
        'soport',
        'confidentiality',
        'enable_expediente',
        'full_conversation',
        'select',
        'delete',
        'medium_tecnology',
        'not_transferable_central',
        'description_final',
        'type'
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
        return seriesSubSeries::class;
    }

    public function all_series_subseries(){
        return seriesSubSeries::orderBy('no_serie', 'ASC')->orderBy('no_subserie','ASC')->get();
    }
}
