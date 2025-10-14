<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CompositionEquipmentLeca
 * @package Modules\Maintenance\Models
 * @version April 29, 2021, 11:46 am -05
 *
 * @property Modules\Maintenance\Models\MantResumeEquipmentMachineryLeca $mantResumeEquipmentMachineryLeca
 * @property string $accessory_parts
 * @property string $reference
 * @property string $observation
 * @property integer $mant_resume_equipment_machinery_leca_id
 */
class CompositionEquipmentLeca extends Model
{
    use SoftDeletes;

    public $table = 'mant_composition_equipment_machinery_leca';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'accessory_parts',
        'reference',
        // 'observation',
        'mant_resume_equipment_machinery_leca_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'accessory_parts' => 'string',
        'reference' => 'string',
        // 'observation' => 'string',
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
