<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\RequestNeedHistory;
use App\Repositories\BaseRepository;

/**
 * Class RequestNeedHistoryRepository
 * @package Modules\Maintenance\Repositories
 * @version November 29, 2023, 2:55 pm -05
*/

class RequestNeedHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_nombre',
        'observacion',
        'estado',
        'users_id',
        'mant_sn_request_id'
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
        return RequestNeedHistory::class;
    }
}
