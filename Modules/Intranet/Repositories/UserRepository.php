<?php

namespace Modules\Intranet\Repositories;

use App\User;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package Modules\Intranet\Repositories
 * @version June 17, 2020, 3:12 pm UTC
*/

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_cargo',
        'id_dependencia',
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
        'enable_second_password',
        'second_password',
        'accept_service_terms',
        'user_type',
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
        return User::class;
    }
}
