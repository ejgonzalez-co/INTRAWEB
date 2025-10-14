<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CharacteristicsEquipment
 * @package Modules\Maintenance\Models
 * @version April 23, 2021, 8:41 am -05
 *
 * @property Modules\Maintenance\Models\MantResumeEquipmentMachinery $mantResumeEquipmentMachinery
 * @property string $accessory_parts
 * @property string $amount
 * @property string $reference_part_number
 * @property integer $mant_resume_equipment_machinery_id
 */
class CharacteristicsEquipment extends Model
{
    use SoftDeletes;

    public $table = 'mant_characteristics_equipment_machinery';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'accessory_parts',
        'amount',
        'reference_part_number',
        'mant_resume_equipment_machinery_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'accessory_parts' => 'string',
        'amount' => 'string',
        'reference_part_number' => 'string',
        'mant_resume_equipment_machinery_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_resume_equipment_machinery_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeEquipmentMachinery()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeEquipmentMachinery::class, 'mant_resume_equipment_machinery_id');
    }
}
