<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EquipmentPurchaseDetailsBackup
 * @package Modules\HelpTable\Models
 * @version January 25, 2023, 8:18 pm -05
 *
 * @property \Modules\HelpTable\Models\HtTicEquipmentResumeBackup $htTicEquipmentResumeBackups
 * @property integer $ht_tic_equipment_resume_backups_id
 * @property string $contract_number
 * @property string $date
 * @property string $provider
 * @property string $warranty_in_years
 * @property string $contract_total_value
 * @property string $status
 * @property string $warranty_termination_date
 */
class EquipmentPurchaseDetailsBackup extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_equipment_purchase_details_backup';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'ht_tic_equipment_resume_backups_id',
        'contract_number',
        'date',
        'provider',
        'warranty_in_years',
        'contract_total_value',
        'status',
        'warranty_termination_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_equipment_resume_backups_id' => 'integer',
        'contract_number' => 'string',
        'date' => 'date',
        'provider' => 'string',
        'warranty_in_years' => 'string',
        'contract_total_value' => 'string',
        'status' => 'string',
        'warranty_termination_date' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_equipment_resume_backups_id' => 'nullable',
        'contract_number' => 'nullable|string|max:50',
        'date' => 'nullable',
        'provider' => 'nullable|string|max:50',
        'warranty_in_years' => 'nullable|string|max:4',
        'contract_total_value' => 'nullable|string|max:20',
        'status' => 'nullable|string|max:12',
        'warranty_termination_date' => 'nullable|string|max:4',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function htTicEquipmentResumeBackups() {
        return $this->belongsTo(\Modules\HelpTable\Models\HtTicEquipmentResumeBackup::class, 'ht_tic_equipment_resume_backups_id');
    }
}
