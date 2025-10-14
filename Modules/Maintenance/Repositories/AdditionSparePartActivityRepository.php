<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\AdditionSparePartActivity;
use App\Repositories\BaseRepository;

class AdditionSparePartActivityRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'type_request',
        'admin_observation',
        'provider_observation',
        'total_solicitud',
        'order_id',
        'request_id',
        'status',
        'admin_creator_id',
        'provider_creator_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AdditionSparePartActivity::class;
    }
}
