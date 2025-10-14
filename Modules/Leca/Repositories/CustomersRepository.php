<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\Customers;
use App\Repositories\BaseRepository;

/**
 * Class CustomersRepository
 * @package Modules\Leca\Repositories
 * @version November 8, 2021, 8:48 am -05
*/

class CustomersRepository extends BaseRepository
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
        'extension',
        'cell_number',
        'description',
        'state'
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
        return Customers::class;
    }
}
