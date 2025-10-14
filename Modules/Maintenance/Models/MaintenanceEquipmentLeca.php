<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MaintenanceEquipmentLeca
 * @package Modules\Maintenance\Models
 * @version April 29, 2021, 11:46 am -05
 *
 * @property Modules\Maintenance\Models\MantResumeEquipmentMachineryLeca $mantResumeEquipmentMachineryLeca
 * @property string $name
 * @property string $acceptance_requirements
 * @property integer $mant_resume_equipment_machinery_leca_id
 */
class MaintenanceEquipmentLeca extends Model
{
    use SoftDeletes;

    public $table = 'mant_maintenance_equipment_machinery_leca';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'acceptance_requirements',
        'mant_resume_equipment_machinery_leca_id',
        'verification',
        'calibration_under_accreditation',
        'rule_reference_calibration',
        'criteria_acceptance_certificate',
        'measure_standard'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'acceptance_requirements' => 'string',
        'verification' => 'string',
        'calibration_under_accreditation' => 'string',
        'rule_reference_calibration' => 'string',
        'criteria_acceptance_certificate' => 'string',
        'measure_standard' => 'string',
        'mant_resume_equipment_machinery_leca_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_resume_equipment_machinery_leca_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeEquipmentMachineryLeca()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\MantResumeEquipmentMachineryLeca::class, 'mant_resume_equipment_machinery_leca_id');
    }
}
