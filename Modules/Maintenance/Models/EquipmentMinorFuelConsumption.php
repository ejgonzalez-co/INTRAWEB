<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class EquipmentMinorFuelConsumption
 * @package Modules\Maintenance\Models
 * @version August 27, 2021, 2:31 pm -05
 *
 * @property Modules\Maintenance\Models\MantMinorEquipmentFuel $mantMinorEquipmentFuel
 * @property integer $mant_minor_equipment_fuel_id
 * @property integer $mant_resume_equipment_machinery_id
 * @property integer $dependencias_id
 * @property string $supply_date
 * @property string $process
 * @property string $equipment_description
 * @property number $gallons_supplied
 * @property string $name_receives_equipment
 */
class EquipmentMinorFuelConsumption extends Model {
    use SoftDeletes;

    public $table = 'mant_equipment_fuel_consumption';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_minor_equipment_fuel_id',
        'mant_resume_equipment_machinery_id',
        'dependencias_id',
        'supply_date',
        'process',
        'equipment_description',
        'gallons_supplied',
        'name_receives_equipment',
        'liter_supplied',
        'fuel_input',
        'migrado',
        'fecha_migracion',
        'fuel_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_minor_equipment_fuel_id' => 'integer',
        'mant_resume_equipment_machinery_id' => 'integer',
        'dependencias_id' => 'integer',
        'supply_date' => 'string',
        'gallons_supplied' => 'float',
        'name_receives_equipment' => 'string',
        'created_at'=> 'string',
        'updated_at'=> 'string',
        'liter_supplied'=> 'float',
        'fuel_input'=> 'string',
        'migrado'=>'integer',
        'fecha_migracion'=>'date',
        'fuel_type'=> 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_minor_equipment_fuel_id' => 'required',
        'mant_resume_equipment_machinery_id' => 'required',
        'dependencias_id' => 'required',
        'supply_date' => 'nullable',
        'gallons_supplied' => 'nullable|numeric',
        'name_receives_equipment' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'liter_supplied'=> 'nullable',
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
    public function mantMinorEquipmentFuel() {
        return $this->belongsTo(\Modules\Maintenance\Models\MinorEquipmentFuel::class, 'mant_minor_equipment_fuel_id');
    }

/**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeEquipmentMachinery() {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeEquipmentMachinery::class, 'mant_resume_equipment_machinery_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantDocumentsMinorEquipments() {
        return $this->hasMany(\Modules\Maintenance\Models\DocumentsMinorEquipment::class, 'mant_equipment_fuel_consumption_id');
    }
}
