<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\AssetManagement;
use App\Repositories\BaseRepository;

/**
 * Class AssetManagementRepository
 * @package Modules\Maintenance\Repositories
 * @version February 12, 2024, 2:19 pm -05
*/

class AssetManagementRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre_activo',
        'tipo_mantenimiento',
        'kilometraje_actual',
        'kilometraje_recibido_proveedor',
        'nombre_proveedor',
        'no_salida_almacen',
        'no_factura',
        'no_solicitud',
        'actividad',
        'repuesto'
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
        return AssetManagement::class;
    }
}
