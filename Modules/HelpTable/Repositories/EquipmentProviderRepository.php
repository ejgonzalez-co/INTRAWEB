<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\EquipmentProvider;
use App\Repositories\BaseRepository;

/**
 * Class EquipmentProviderRepository
 * @package Modules\HelpTable\Repositories
 * @version January 27, 2023, 8:03 am -05
*/

class EquipmentProviderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'identification_number',
        'contract_number',
        'fullname',
        'email',
        'phone',
        'address',
        'contract_start_date',
        'contract_end_date',
        'status',
        'observations'
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
        return EquipmentProvider::class;
    }
}
