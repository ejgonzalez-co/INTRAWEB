<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\RequestNeedOrders;
use App\Repositories\BaseRepository;

/**
 * Class RequestNeedOrdersRepository
 * @package Modules\Maintenance\Repositories
 * @version December 25, 2023, 12:48 pm -05
*/

class RequestNeedOrdersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tipo_mantenimiento',
        'observacion',
        'tipo_solicitud',
        'usuario',
        'estado',
        'consecutivo',
        'rol_asignado',
        'bodega',
        'mant_sn_request_id',
        'current_mileage_or_hourmeter',
        'mileage_or_hourmeter_received',
        'ordenes_entradas',
        'date_entry',
        'date_work_completion',
        'url_evidences',
        'provider_observation',
        'mileage_out_stock',
        'supplier_end_date'
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
        return RequestNeedOrders::class;
    }
}
