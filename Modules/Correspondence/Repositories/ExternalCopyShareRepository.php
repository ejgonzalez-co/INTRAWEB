<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\ExternalCopyShare;
use App\Repositories\BaseRepository;

/**
 * Class ExternalCopyShareRepository
 * @packageModules\Correspondence\Repositories
 * @version April 19, 2022, 6:27 pm -05
*/

class ExternalCopyShareRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'is_readed',
        'times',
        'name',
        'correspondence_external_id',
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
        return ExternalCopyShare::class;
    }
}
