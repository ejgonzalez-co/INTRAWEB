<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicRequestHistory;
use App\Repositories\BaseRepository;

/**
 * Class TicRequestHistoryRepository
 * @package Modules\HelpTable\Repositories
 * @version June 5, 2021, 11:38 am -05
*/

class TicRequestHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ht_tic_requests_id',
        'ht_tic_type_request_id',
        'ht_tic_request_status_id',
        'assigned_by_id',
        'assigned_by_name',
        'users_id',
        'users_name',
        'assigned_user_name',
        'assigned_user_id',
        'ht_tic_type_tic_categories_id',
        'priority_request',
        'affair',
        'floor',
        'assignment_date',
        'prox_date_to_expire',
        'expiration_date',
        'date_attention',
        'closing_date',
        'reshipment_date',
        'next_hour_to_expire',
        'hours',
        'description',
        'tracing',
        'request_status',
        'survey_status',
        'time_line',
        'support_type',
        'username_requesting_requirement',
        'location',
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
        return TicRequestHistory::class;
    }
}
