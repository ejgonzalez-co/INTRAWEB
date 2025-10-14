<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\EquipmentPurchaseDetail;
use App\Repositories\BaseRepository;

class EquipmentPurchaseDetailRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ht_tic_equipment_resume_id',
        'contract_number',
        'date',
        'provider',
        'warranty_in_years',
        'contract_total_value',
        'status',
        'warranty_termination_date'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EquipmentPurchaseDetail::class;
    }
}
