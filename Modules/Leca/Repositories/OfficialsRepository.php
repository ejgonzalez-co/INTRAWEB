<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\Officials;
use App\Repositories\BaseRepository;

/**
 * Class OfficialsRepository
 * @package Modules\Leca\Repositories
 * @version November 17, 2021, 10:26 am -05
*/

class OfficialsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'pin',
        'password',
        'identification_number',
        'name',
        'email',
        'telephone',
        'direction',
        'charge',
        'functions',
        'state',
        'receptionist',
        'firm'
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
        return Officials::class;
    }
}
