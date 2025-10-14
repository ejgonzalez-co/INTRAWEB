<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\ExternalRecipient;
use App\Repositories\BaseRepository;

/**
 * Class ExternalRecipientRepository
 * @packageModules\Correspondence\Repositories
 * @version January 19, 2022, 3:30 pm -05
*/

class ExternalRecipientRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'is_readed',
        'times',
        'correspondence_external_id',
        'users_id',
        'work_groups_id',
        'cargos_id',
        'dependencias_id'
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
        return ExternalRecipient::class;
    }
}
