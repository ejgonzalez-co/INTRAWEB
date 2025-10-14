<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentsMinorEquipment
 * @package Modules\Maintenance\Models
 * @version August 27, 2021, 2:33 pm -05
 *
 * @property Modules\Maintenance\Models\MantMinorEquipmentFuel $mantMinorEquipmentFuel
 * @property integer $mant_minor_equipment_fuels_id
 * @property integer $mant_equipment_fuel_consumption_id
 * @property string $name
 * @property string $url
 */
class DocumentsMinorEquipment extends Model {
    use SoftDeletes;

    public $table = 'mant_documents_minor_equipment';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

     
    
    public $fillable = [
        'mant_minor_equipment_fuels_id',
        'mant_equipment_fuel_consumption_id',
        'name',
        'url',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_minor_equipment_fuels_id' => 'integer',
        'mant_equipment_fuel_consumption_id' => 'integer',
        'name' => 'string',
        'url' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'observation' => 'nullable|string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantMinorEquipmentFuel() {
        return $this->belongsTo(\Modules\Maintenance\Models\MinorEquipmentFuel::class, 'mant_minor_equipment_fuels_id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantMinorEquipmentFuelConsume() {
        return $this->belongsTo(\Modules\Maintenance\Models\EquipmentMinorFuelConsumption::class, 'mant_equipment_fuel_consumption_id');
    }
}
