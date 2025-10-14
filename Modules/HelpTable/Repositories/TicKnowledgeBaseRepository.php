<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicKnowledgeBase;
use App\Repositories\BaseRepository;

/**
 * Class TicKnowledgeBaseRepository
 * @package Modules\HelpTable\Repositories
 * @version June 5, 2021, 10:16 am -05
*/

class TicKnowledgeBaseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ht_tic_type_request_id',
        'users_id',
        'ht_tic_requests_id',
        'affair',
        'knowledge_description',
        'attached',
        'knowledge_state'
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
        return TicKnowledgeBase::class;
    }
}
