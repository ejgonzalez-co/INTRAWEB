<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\UserHistory;
use App\Repositories\BaseRepository;

/**
 * Class UserHistoryRepository
 * @package Modules\Intranet\Repositories
 * @version July 27, 2020, 9:05 am -05
*/

class UserHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'cargos_id',
        'dependencias_id',
        'observation',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'url_img_profile',
        'url_digital_signature',
        'username',
        'block',
        'sendEmail',
        'lastvisitDate',
        'registerDate',
        'lastResetTime',
        'resetCount',
        'logapp',
        'birthday',
        'contract_start',
        'contract_end',
        'certificatecrt',
        'certificatekey',
        'certificatepfx',
        'localcertificatecrt',
        'localcertificatekey',
        'localcertificatepfx',
        'cf_user_id',
        'enable_second_password',
        'second_password',
        'accept_service_terms',
        'autorizado_firmar',
        'sedes_id',
        'funcionario_encargado',
        'fecha_inicio_encargo',
        'fecha_fin_encargo'


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
        return UserHistory::class;
    }
}
