<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\AdditionNeed;
use App\Repositories\BaseRepository;

class AdditionNeedRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'need',
        'description',
        'unit_measurement',
        'unit_value',
        'iva',
        'amount_requested',
        'valor_total',
        'maintenance_type',
        'addition_id',
        'is_approved',
        'is_returned'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AdditionNeed::class;
    }
}
