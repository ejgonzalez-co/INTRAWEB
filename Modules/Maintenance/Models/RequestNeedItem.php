<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestNeedItem
 * @package Modules\Maintenance\Models
 * @version November 29, 2023, 2:55 pm -05
 *
 * @property Modules\Maintenance\Models\MantSnRequest $mantSnRequest
 * @property string $proceso_id
 * @property string $tipo_solicitud
 * @property string $tipo_necesidad
 * @property string $tipo_activo
 * @property string $activo_id
 * @property string $rubro_nombre
 * @property string $rubro_id
 * @property string $rubro_objeto_contrato_id
 * @property string $valor_disponible
 * @property string $necesidad
 * @property string $descripcion
 * @property string $unidad_medida
 * @property string $valor_unitario
 * @property string $cantidad_solicitada
 * @property string $IVA
 * @property string $valor_total
 * @property string $estado
 * @property integer $mant_sn_request_id
 */
class RequestNeedItem extends Model {
    use SoftDeletes;

    public $table = 'mant_sn_request_needs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'proceso_id',
        'tipo_solicitud',
        'tipo_necesidad',
        'tipo_activo',
        'activo_id',
        'rubro_nombre',
        'rubro_id',
        'rubro_objeto_contrato_id',
        'valor_disponible',
        'necesidad',
        'descripcion',
        'unidad_medida',
        'valor_unitario',
        'cantidad_solicitada',
        'IVA',
        'valor_total',
        'estado',
        'mant_sn_request_id',
        'descripcion_nombre',
        'tipo_mantenimiento',
        'codigo',
        'cantidad_final',
        'cantidad_entrada',
        'total_value',
        'unidad_medida_conversion',
        'cantidad_solicitada_conversion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'proceso_id' => 'string',
        'tipo_solicitud' => 'string',
        'tipo_necesidad' => 'string',
        'tipo_activo' => 'string',
        'activo_id' => 'string',
        'rubro_nombre' => 'string',
        'rubro_id' => 'string',
        'rubro_objeto_contrato_id' => 'string',
        'valor_disponible' => 'string',
        'necesidad' => 'string',
        'descripcion' => 'string',
        'unidad_medida' => 'string',
        'valor_unitario' => 'string',
        'cantidad_solicitada' => 'string',
        'IVA' => 'string',
        'valor_total' => 'string',
        'estado' => 'string',
        'mant_sn_request_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'proceso_id' => 'nullable|string|max:45',
        'tipo_solicitud' => 'nullable|string|max:45',
        'tipo_necesidad' => 'nullable|string|max:45',
        'tipo_activo' => 'nullable|string|max:45',
        'activo_id' => 'nullable|string|max:45',
        'rubro_nombre' => 'nullable|string|max:45',
        'rubro_id' => 'nullable|string|max:45',
        'rubro_objeto_contrato_id' => 'nullable|string|max:45',
        'valor_disponible' => 'nullable|string|max:45',
        'necesidad' => 'nullable|string|max:45',
        'descripcion' => 'nullable|string|max:45',
        'unidad_medida' => 'nullable|string|max:45',
        'valor_unitario' => 'nullable|string|max:45',
        'cantidad_solicitada' => 'nullable|string|max:45',
        'IVA' => 'nullable|string|max:45',
        'valor_total' => 'nullable|string|max:45',
        'estado' => 'nullable|string|max:45',
        'mant_sn_request_id' => 'required'
    ];

    protected $appends = ["descripcion_datos"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function solicitud() {
        return $this->belongsTo(\Modules\Maintenance\Models\RequestNeed::class, 'mant_sn_request_id');
    }

    public function getDescripcionDatosAttribute(){

        if($this->tipo_solicitud == 'Stock'){
            return ["description" => $this->descripcion,"articulo" => $this->descripcion_nombre, "unidad_medida" => $this->unidad_medida, "valor_unitario" => $this->valor_unitario, "IVA" => $this->IVA,"cantidad_solicitada" => $this->cantidad_solicitada, "valor_total" => $this->valor_total];
        }

        if($this->necesidad=='Actividades'){
            return ImportActivitiesProviderContract::find($this->descripcion);
        }else{

            return ImportSparePartsProviderContract::find($this->descripcion);

        }
    }


    // public function descripcionDatos() {
    //     // return $this->necesidad;
    //     if($this->necesidad=='Actividades'){
    //         return $this->belongsTo(\Modules\Maintenance\Models\ImportActivitiesProviderContract::class, 'descripcion');
    //     }else{
    //         return $this->belongsTo(\Modules\Maintenance\Models\ImportSparePartsProviderContract::class, 'descripcion');

    //     }
    // }


}
