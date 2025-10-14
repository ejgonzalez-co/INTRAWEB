<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicRequest;
use App\Repositories\BaseRepository;

/**
 * Class TicRequestRepository
 * @package Modules\HelpTable\Repositories
 * @version June 4, 2021, 4:18 pm -05
*/

class TicRequestRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ht_tic_type_request_id',
        'ht_tic_request_status_id',
        'assigned_by_id',
        'users_id',
        'assigned_user_id',
        'ht_tic_type_tic_categories_id',
        'priority_request',
        'affair',
        'floor',
        'assignment_date',
        'prox_date_to_expire',
        'expiration_date',
        'date_attention',
        'closing_date',
        'reshipment_date',
        'next_hour_to_expire',
        'hours',
        'description',
        'tracing',
        'request_status',
        'survey_status',
        'time_line',
        'support_type',
        'username_requesting_requirement',
        'location',
        'assigned_by_name',
        'users_name',
        'assigned_user_name',
        'notification_expired',
        'acceso_remoto',
        'codigo_conexion',
        'clave_conexion'
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
        return TicRequest::class;
    }
}
