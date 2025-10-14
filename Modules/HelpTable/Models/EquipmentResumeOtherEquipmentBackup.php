<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EquipmentResumeOtherEquipmentBackup
 * @package Modules\HelpTable\Models
 * @version January 25, 2023, 8:14 pm -05
 *
 * @property \Modules\HelpTable\Models\HtTicEquipmentResumeBackup $htTicEquipmentResumeBackups
 * @property integer $ht_tic_equipment_resume_backups_id
 * @property string $inventory_number
 * @property string $mark
 * @property string $model
 * @property string $serial
 * @property string $monitor_value
 * @property string $contract_number
 */
class EquipmentResumeOtherEquipmentBackup extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_hardware_configuration_other_equipment_backup';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'ht_tic_equipment_resume_backups_id',
        'inventory_number',
        'mark',
        'model',
        'serial',
        'monitor_value',
        'contract_number'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_equipment_resume_backups_id' => 'integer',
        'inventory_number' => 'string',
        'mark' => 'string',
        'model' => 'string',
        'serial' => 'string',
        'monitor_value' => 'string',
        'contract_number' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_equipment_resume_backups_id' => 'required',
        'inventory_number' => 'nullable|string|max:50',
        'mark' => 'nullable|string|max:50',
        'model' => 'nullable|string|max:50',
        'serial' => 'nullable|string|max:50',
        'monitor_value' => 'nullable|string|max:20',
        'contract_number' => 'nullable|string|max:50',
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
