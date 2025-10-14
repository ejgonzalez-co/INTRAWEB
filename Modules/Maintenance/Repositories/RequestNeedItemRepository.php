<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\RequestNeedItem;
use App\Repositories\BaseRepository;

/**
 * Class RequestNeedItemRepository
 * @package Modules\Maintenance\Repositories
 * @version November 29, 2023, 2:55 pm -05
*/

class RequestNeedItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'proceso_id',
        'tipo_solicitud',
        'tipo_necesidad',
        'tipo_activo',
        'activo_id',
        'rubro_nombre',
        'rubro_id',
        'rubro_objeto_contrato_id',
        'valor_disponible',
        'necesidad',
        'descripcion',
        'unidad_medida',
        'valor_unitario',
        'cantidad_solicitada',
        'IVA',
        'valor_total',
        'estado',
        'mant_sn_request_id',
        'total_value'
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
        return RequestNeedItem::class;
    }
}
