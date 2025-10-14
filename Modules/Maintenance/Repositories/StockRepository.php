<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\Stock;
use App\Repositories\BaseRepository;

/**
 * Class StockRepository
 * @package Modules\Maintenance\Repositories
 * @version February 5, 2024, 10:54 am -05
*/

class StockRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_solicitud_necesidad',
        'codigo',
        'articulo',
        'grupo',
        'cantidad',
        'costo_unitario',
        'total'
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
        return Stock::class;
    }
}
