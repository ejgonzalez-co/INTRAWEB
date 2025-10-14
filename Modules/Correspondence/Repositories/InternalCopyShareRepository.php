<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\InternalCopyShare;
use App\Repositories\BaseRepository;

/**
 * Class InternalCopyShareRepository
 * @packageModules\Correspondence\Repositories
 * @version April 19, 2022, 6:27 pm -05
*/

class InternalCopyShareRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'is_readed',
        'times',
        'name',
        'correspondence_internal_id',
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
        return InternalCopyShare::class;
    }
}
