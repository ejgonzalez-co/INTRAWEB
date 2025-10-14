<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ReporManagementAttachment;
use App\Repositories\BaseRepository;

/**
 * Class ReporManagementAttachmentRepository
 * @package Modules\Leca\Repositories
 * @version December 10, 2022, 9:40 pm -05
*/

class ReporManagementAttachmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_rm_report_management_id',
        'users_id',
        'name',
        'user_name',
        'attachment',
        'status',
        'comments'
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
        return ReporManagementAttachment::class;
    }
}
