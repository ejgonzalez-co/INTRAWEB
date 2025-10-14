<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\QuotaPartsNewsUsers;
use App\Repositories\BaseRepository;

/**
 * Class QuotaPartsNewsUsersRepository
 * @package Modules\Workhistories\Repositories
 * @version December 10, 2020, 3:43 pm -05
*/

class QuotaPartsNewsUsersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'new',
        'type_document',
        'users_name',
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
        return QuotaPartsNewsUsers::class;
    }
}
