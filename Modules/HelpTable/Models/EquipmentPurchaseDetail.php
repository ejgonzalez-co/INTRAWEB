<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

use Illuminate\Database\Eloquent\Model;

class EquipmentPurchaseDetail extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_equipment_purchase_details';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'ht_tic_equipment_resume_id',
        'contract_number',
        'date',
        'provider',
        'warranty_in_years',
        'contract_total_value',
        'status',
        'warranty_termination_date'
    ];

    protected $casts = [
        'id'=>'integer',
        'contract_number' => 'string',
        'date' => 'date',
        'provider' => 'string',
        'warranty_in_years' => 'string',
        'contract_total_value' => 'string',
        'status' => 'string',
        'warranty_termination_date' => 'date'
    ];

    public static array $rules = [
        'contract_number' => 'nullable|string|max:50',
        'date' => 'nullable',
        'provider' => 'nullable|string|max:50',
        'warranty_in_years' => 'nullable|string|max:4',
        'contract_total_value' => 'nullable|string|max:20',
        'status' => 'nullable|string|max:12',
        'warranty_termination_date' => 'nullable|date',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

      /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }
}
