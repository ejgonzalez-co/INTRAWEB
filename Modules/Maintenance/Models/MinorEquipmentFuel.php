<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Modules\Maintenance\Models\MinorEquipmentFuel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use DateTimeInterface;

/**
 * Class MinorEquipmentFuel
 * @package Modules\Maintenance\Models
 * @version August 27, 2021, 2:29 pm -05
 *
 * @property Modules\Maintenance\Models\Dependencia $dependencias
 * @property Modules\Maintenance\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $mantDocumentsMinorEquipments
 * @property \Illuminate\Database\Eloquent\Collection $mantEquipmentFuelConsumptions
 * @property \Illuminate\Database\Eloquent\Collection $mantHistoryMinorEquipments
 * @property integer $dependencias_id
 * @property integer $users_id
 * @property string $responsible_process
 * @property string $supply_date
 * @property time $supply_hour
 * @property string $start_date_fortnight
 * @property string $end_date_fortnight
 * @property number $initial_fuel_balance
 * @property number $more_buy_fortnight
 * @property number $less_fuel_deliveries
 * @property number $final_fuel_balance
 * @property string $bill_number
 * @property number $gallon_value
 * @property number $checked_fuel
 * @property number $cost_in_pesos
 * @property string $name
 * @property string $position
 * @property string $approved_process
 * @property string $process_leader_name
 */
class MinorEquipmentFuel extends Model {
    use SoftDeletes;

    public $table = 'mant_minor_equipment_fuels';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'dependencias_id',
        'users_id',
        'responsible_process',
        'supply_date',
        'supply_hour',
        'start_date_fortnight',
        'end_date_fortnight',
        'initial_fuel_balance',
        'more_buy_fortnight',
        'less_fuel_deliveries',
        'final_fuel_balance',
        'bill_number',
        'gallon_value',
        'checked_fuel',
        'cost_in_pesos',
        'name',
        'position',
        'approved_process',
        'process_leader_name',
        'fuel_type'
    ];

    protected $appends = [
        'total_consume_fuel',
        'exists_after',
        'name_leader',
        'total_fuel_avaible',
        'more_buy_fortnight_formated'
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

    //Envia el combustible disponible
    public function getTotalFuelAvaibleAttribute(){
        $total=0;
        foreach($this->mantEquipmentFuelConsumptions as $mantEquipment){
             $total+=$mantEquipment->gallons_supplied;
        }        
        $total=$this->final_fuel_balance-$total;
        // return number_format($total,4,",",".");
        return $total;


    }

    //Envia el total de consumo por equipo
    public function getTotalConsumeFuelAttribute() {
        $total=0;
        foreach($this->mantEquipmentFuelConsumptions as $mantEquipment){
             $total+=$mantEquipment->gallons_supplied;
        }        
        // return number_format($total,4,",",".");
        return $total;

    }

    //Envia el total de consumo por equipo
    public function getMoreBuyFortnightFormatedAttribute() {  
        return number_format($this->more_buy_fortnight,4,",",".");
    }

     //Envia el si existe un registro despues
     public function getExistsAfterAttribute() 
     {
         return MinorEquipmentFuel::where('dependencias_id', $this->dependencias_id)->where('fuel_type', $this->fuel_type)->where('created_at', '>', $this->created_at)->exists();
     }

    //Envia el nombre
    public function getNameLeaderAttribute() {
        $user=User::find($this->process_leader_name);
        $name=$user['name'];
        return $name;
    }
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dependencias_id' => 'integer',
        'users_id' => 'integer',
        'responsible_process' => 'integer',
        'supply_date' => 'string',
        'start_date_fortnight' => 'string',
        'end_date_fortnight' => 'string',
        'initial_fuel_balance' => 'float',
        'more_buy_fortnight' => 'float',
        'less_fuel_deliveries' => 'float',
        'final_fuel_balance' => 'float',
        'bill_number' => 'string',
        'gallon_value' => 'float',
        'checked_fuel' => 'float',
        'cost_in_pesos' => 'float',
        'name' => 'string',
        'position' => 'string',
        'approved_process' => 'string',
        'process_leader_name' => 'string',
        'fuel_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'responsible_process' => 'nullable|string|max:255',
        'supply_date' => 'nullable',
        'supply_hour' => 'nullable',
        'start_date_fortnight' => 'nullable',
        'end_date_fortnight' => 'nullable',
        'initial_fuel_balance' => 'nullable|numeric',
        'more_buy_fortnight' => 'nullable|numeric',
        'less_fuel_deliveries' => 'nullable|numeric',
        'final_fuel_balance' => 'nullable|numeric',
        'bill_number' => 'nullable|string|max:255',
        'gallon_value' => 'nullable|numeric',
        'checked_fuel' => 'nullable|numeric',
        'cost_in_pesos' => 'nullable|numeric',
        'name' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'approved_process' => 'nullable|string|max:255',
        'process_leader_name' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciasResponsable() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'responsible_process');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciasAprobo() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'approved_process');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantDocumentsMinorEquipments() {
        return $this->hasMany(\Modules\Maintenance\Models\DocumentsMinorEquipment::class, 'mant_minor_equipment_fuels_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantEquipmentFuelConsumptions() {
        return $this->hasMany(\Modules\Maintenance\Models\EquipmentMinorFuelConsumption::class, 'mant_minor_equipment_fuel_id')->with(['dependencias','mantResumeEquipmentMachinery','mantDocumentsMinorEquipments']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantHistoryMinorEquipments() {
        return $this->hasMany(\Modules\Maintenance\Models\HistoryEquipmentMinor::class, 'mant_minor_equipment_fuel_id')->with('dependencias','dependenciasApproved','users');
    }

    
}
