<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\Sent;
use App\Repositories\BaseRepository;

/**
 * Class SentRepository
 * @packageModules\Correspondence\Repositories
 * @version January 5, 2022, 10:13 am -05
*/

class SentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'consecutive',
        'state',
        'type_document',
        'title',
        'matter',
        'attached',
        'folios',
        'annexes',
        'channel',
        'received_associated',
        'guide',
        'sent_to_id',
        'sent_to_name',
        'remitting_id',
        'remitting_name',
        'remitting_dependency',
        'origin',
        'id_pqr_finished',
        'create_user_id'
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
        return Sent::class;
    }
}
