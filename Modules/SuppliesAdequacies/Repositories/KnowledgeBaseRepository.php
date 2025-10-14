<?php

namespace Modules\SuppliesAdequacies\Repositories;

use Modules\SuppliesAdequacies\Models\KnowledgeBase;
use App\Repositories\BaseRepository;

/**
 * Class KnowledgeBaseRepository
 * @package Modules\SuppliesAdequacies\Repositories
 * @version November 14, 2024, 2:41 pm -05
*/

class KnowledgeBaseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_creator',
        'requests_supplies_adjustements_id',
        'knowledge_type',
        'subject_knowledge',
        'description',
        'status',
        'url_attacheds'
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
        return KnowledgeBase::class;
    }
}
