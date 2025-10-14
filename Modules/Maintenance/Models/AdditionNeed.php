<?php

namespace Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionNeed extends Model
{
    use SoftDeletes;

    public $table = 'mant_sn_additions_needs';

    public $fillable = [
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

    protected $casts = [
        'need' => 'string',
        'description' => 'string',
        'unit_measurement' => 'string',
        'maintenance_type' => 'string',
        'is_approved' => 'boolean',
        'is_returned' => 'boolean'
    ];

    public static array $rules = [
        'need' => 'nullable|string|max:11',
        'description' => 'nullable|string|max:300',
        'unit_measurement' => 'nullable|string|max:40',
        'unit_value' => 'nullable',
        'iva' => 'nullable',
        'amount_requested' => 'nullable',
        'valor_total' => 'nullable',
        'maintenance_type' => 'nullable|string|max:10',
        'addition_id' => 'nullable',
        'is_approved' => 'nullable|boolean',
        'is_returned' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function Addition() {
        return $this->belongsTo(\Modules\Maintenance\Models\AdditionSparePartActivity::class, 'addition_id');
    }
}
