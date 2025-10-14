<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\Providers;
use App\Repositories\BaseRepository;

/**
 * Class ProvidersRepository
 * @package Modules\Maintenance\Repositories
 * @version February 18, 2021, 11:48 am -05
*/

class ProvidersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_person',
        'document_type',
        'identification',
        'name',
        'mail',
        'regime',
        'phone',
        'address',
        'municipality',
        'department',
        'name_rep',
        'document_type_rep',
        'identification_rep',
        'phone_rep',
        'mail_rep',
        'description',
        'state',
        'mant_types_activity_id'
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
        return Providers::class;
    }
}
