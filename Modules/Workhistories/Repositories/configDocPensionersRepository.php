<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\configDocPensioners;
use App\Repositories\BaseRepository;

/**
 * Class configDocPensionersRepository
 * @package Modules\Workhistories\Repositories
 * @version December 4, 2020, 11:52 am -05
*/

class configDocPensionersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'state',
        'users_id'
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
        return configDocPensioners::class;
    }
}
