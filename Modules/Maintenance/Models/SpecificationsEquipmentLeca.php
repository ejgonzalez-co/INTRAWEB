<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SpecificationsEquipmentLeca
 * @package Modules\Maintenance\Models
 * @version May 3, 2021, 11:38 am -05
 *
 * @property Modules\Maintenance\Models\MantResumeEquipmentLeca $mantResumeEquipmentLeca
 * @property string $calibration_verification_points
 * @property string $reference_standard_calibration_verification
 * @property string $acceptance_requirements
 * @property integer $mant_resume_equipment_leca_id
 */
class SpecificationsEquipmentLeca extends Model
{
    use SoftDeletes;

    public $table = 'mant_technical_specifications_equipment_leca';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'calibration_verification_points',
        'reference_standard_calibration_verification',
        'acceptance_requirements',
        'mant_resume_equipment_leca_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'calibration_verification_points' => 'string',
        'reference_standard_calibration_verification' => 'string',
        'acceptance_requirements' => 'string',
        'mant_resume_equipment_leca_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_resume_equipment_leca_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeEquipmentLeca()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeEquipmentLeca::class, 'mant_resume_equipment_leca_id');
    }
}
