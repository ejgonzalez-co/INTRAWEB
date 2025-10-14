<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\RequestNeed;
use App\Repositories\BaseRepository;

/**
 * Class RequestNeedRepository
 * @package Modules\Maintenance\Repositories
 * @version November 29, 2023, 2:54 pm -05
*/

class RequestNeedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_administration_cost_items_id',
        'tipo_solicitud',
        'tipo_necesidad',
        'tipo_activo',
        'activo_id',
        'rubro_nombre',
        'rubro_id',
        'rubro_objeto_contrato_id',
        'valor_disponible',
        'observacion',
        'estado',
        'dependencias_id',
        'users_id',
        'approving_user_id',
        'url_documents',
        'approval_date',
        'approval_justification',
        'invoice_no',
        'date_supervisor_submission',
        'supervisor_observation'
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
        return RequestNeed::class;
    }
}
