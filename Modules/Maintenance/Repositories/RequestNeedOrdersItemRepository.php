<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\RequestNeedOrdersItem;
use App\Repositories\BaseRepository;

/**
 * Class RequestNeedOrdersItemRepository
 * @package Modules\Maintenance\Repositories
 * @version December 25, 2023, 12:34 pm -05
*/

class RequestNeedOrdersItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'descripcion',
        'descripcion_nombre',
        'unidad',
        'cantidad',
        'tipo_mantenimiento',
        'observacion',
        'estado',
        'mant_sn_orders_id',
        'mant_sn_request_needs_id'
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
        return RequestNeedOrdersItem::class;
    }
}
