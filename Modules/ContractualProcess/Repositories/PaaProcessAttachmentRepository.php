<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PaaProcessAttachment;
use App\Repositories\BaseRepository;

/**
 * Class PaaProcessAttachmentRepository
 * @package Modules\ContractualProcess\Repositories
 * @version September 24, 2021, 9:44 am -05
*/

class PaaProcessAttachmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pc_needs_id',
        'name',
        'attached',
        'description'
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
        return PaaProcessAttachment::class;
    }
}
