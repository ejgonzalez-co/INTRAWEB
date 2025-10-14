<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestNeedOrdersItem
 * @package Modules\Maintenance\Models
 * @version December 25, 2023, 12:34 pm -05
 *
 * @property Modules\Maintenance\Models\MantSnOrder $mantSnOrders
 * @property Modules\Maintenance\Models\MantSnRequestNeed $mantSnRequestNeeds
 * @property string $descripcion
 * @property string $descripcion_nombre
 * @property string $unidad
 * @property string $cantidad
 * @property string $tipo_mantenimiento
 * @property string $observacion
 * @property string $estado
 * @property integer $mant_sn_orders_id
 * @property integer $mant_sn_request_needs_id
 */
class RequestNeedOrdersItem extends Model {
    use SoftDeletes;

    public $table = 'mant_sn_orders_has_needs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_sn_orders_id',
        'mant_sn_request_needs_id',
        'descripcion',
        'descripcion_nombre',
        'unidad',
        'cantidad',
        'tipo_mantenimiento',
        'observacion',
        'estado',
        'mant_sn_orders_id',
        'mant_sn_request_needs_id', // PILAS: Foreign key to mant_sn_request SOLICITUD
        'codigo_entrada',
        'cantidad_entrada',
        'codigo_salida',
        'proveedor_id',
        'tipo_solicitud',
        'tipo_necesidad',
        'mant_sn_request_needs_id_real',
        'cantidad_solicitada_conversion',
        'unidad_medida_conversion'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'descripcion' => 'string',
        'descripcion_nombre' => 'string',
        'unidad' => 'string',
        'cantidad' => 'string',
        'tipo_mantenimiento' => 'string',
        'observacion' => 'string',
        'estado' => 'string',
        'mant_sn_orders_id' => 'integer',
        'mant_sn_request_needs_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'descripcion' => 'nullable|string|max:255',
        'descripcion_nombre' => 'nullable|string|max:255',
        'unidad' => 'nullable|string|max:45',
        'cantidad' => 'nullable|string|max:45',
        'tipo_mantenimiento' => 'nullable|string|max:45',
        'observacion' => 'nullable|string',
        'estado' => 'nullable|string|max:45',
        'mant_sn_orders_id' => 'required',
        'mant_sn_request_needs_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantSnOrders() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantSnOrder::class, 'mant_sn_orders_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantSnRequestNeeds() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantSnRequestNeed::class, 'mant_sn_request_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantRequestNeedItem() {
        return $this->belongsTo(\Modules\Maintenance\Models\RequestNeedItem::class, 'mant_sn_request_needs_id_real');
    }

}
