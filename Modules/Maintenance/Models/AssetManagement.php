<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class AssetManagement
 * @package Modules\Maintenance\Models
 * @version February 12, 2024, 2:19 pm -05
 *
 * @property string $nombre_activo
 * @property string $tipo_mantenimiento
 * @property string $kilometraje_actual
 * @property string $kilometraje_recibido_proveedor
 * @property string $nombre_proveedor
 * @property string $no_salida_almacen
 * @property string $no_factura
 * @property string $no_solicitud
 * @property string $actividad
 * @property string $repuesto
 */
class AssetManagement extends Model {
    use SoftDeletes;

    public $table = 'mant_asset_management';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ['repuestos','actividades'];
    
    public $fillable = [
        'nombre_activo',
        'tipo_mantenimiento',
        'kilometraje_actual',
        'kilometraje_recibido_proveedor',
        'nombre_proveedor',
        'no_salida_almacen',
        'no_factura',
        'no_solicitud',
        'no_orden',
        'actividad',
        'repuesto',
        'unidad_medida',
        'request_id',
        'order_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre_activo' => 'string',
        'tipo_mantenimiento' => 'string',
        'kilometraje_actual' => 'string',
        'kilometraje_recibido_proveedor' => 'string',
        'nombre_proveedor' => 'string',
        'no_salida_almacen' => 'string',
        'no_factura' => 'string',
        'no_solicitud' => 'string',
        'actividad' => 'string',
        'repuesto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_activo' => 'nullable|string|max:100',
        'tipo_mantenimiento' => 'nullable|string|max:10',
        'kilometraje_actual' => 'nullable|string|max:30',
        'kilometraje_recibido_proveedor' => 'nullable|string|max:30',
        'nombre_proveedor' => 'nullable|string|max:100',
        'no_salida_almacen' => 'nullable|string|max:45',
        'no_factura' => 'nullable|string|max:45',
        'no_solicitud' => 'nullable|string|max:45',
        'actividad' => 'nullable|string',
        'repuesto' => 'nullable|string',
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

    public function getRepuestosAttribute(){
        return !is_null($this->repuesto) ?  json_decode($this->repuesto,true) : "";
    }

    public function getActividadesAttribute(){
        return !is_null($this->actividad) ?  json_decode($this->actividad,true) : "";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function RequestNeed() {
        return $this->belongsTo(\Modules\Maintenance\Models\RequestNeed::class, 'request_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function Order() {
        return $this->belongsTo(\Modules\Maintenance\Models\RequestNeedOrders::class, 'order_id');
    }

    
}
