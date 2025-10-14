<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class RequestNeedOrders
 * @package Modules\Maintenance\Models
 * @version December 25, 2023, 12:48 pm -05
 *
 * @property Modules\Maintenance\Models\MantSnRequest $mantSnRequest
 * @property \Illuminate\Database\Eloquent\Collection $mantSnOrdersHasNeeds
 * @property string $tipo_mantenimiento
 * @property string $observacion
 * @property string $tipo_solicitud
 * @property string $usuario
 * @property string $estado
 * @property string $consecutivo
 * @property string $rol_asignado
 * @property string $bodega
 * @property integer $mant_sn_request_id
 */
class RequestNeedOrders extends Model {
    use SoftDeletes;

    public $table = 'mant_sn_orders';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'tipo_mantenimiento',
        'observacion',
        'tipo_solicitud',
        'usuario',
        'estado',
        'consecutivo',
        'rol_asignado',
        'bodega',
        'mant_sn_request_id',
        'rol_asignado_nombre',
        'tramite_almacen',
        'estado_proveedor',
        'numero_salida_almacen',
        'fecha_salida_almacen',
        'fecha_entrada_almacen',
        'numero_factura',
        'numero_entrada_almacen',
        'id_proveedor_externo',
        'users_id',
        'current_mileage_or_hourmeter',
        'mileage_or_hourmeter_received',
        'ordenes_entradas',
        'date_entry',
        'date_work_completion',
        'url_evidences',
        'provider_observation',
        'mileage_out_stock',
        'supplier_end_date',
        'no_factura'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tipo_mantenimiento' => 'string',
        'observacion' => 'string',
        'tipo_solicitud' => 'string',
        'usuario' => 'string',
        'estado' => 'string',
        'consecutivo' => 'string',
        'rol_asignado' => 'string',
        'bodega' => 'string',
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
        'tipo_mantenimiento' => 'nullable|string|max:45',
        'observacion' => 'nullable|string',
        'tipo_solicitud' => 'nullable|string|max:45',
        'usuario' => 'nullable|string|max:45',
        'estado' => 'nullable|string|max:45',
        'consecutivo' => 'nullable|string|max:45',
        'rol_asignado' => 'nullable|string|max:45',
        'bodega' => 'nullable|string|max:45',
        'mant_sn_request_id' => 'required'
    ];

    protected $appends = ["proveedor_nombre",'encrypted_id', 'proveedor_id', 'numero_contrato','additions_spare_part_activities_approved', 'processing_additions'];


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

    public function solicitudPrincipal() {
        return $this->belongsTo(\Modules\Maintenance\Models\RequestNeed::class, 'mant_sn_request_id')->with(['contratoDatos','dependencia','users']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordenesItem() {
        return $this->hasMany(\Modules\Maintenance\Models\RequestNeedOrdersItem::class, 'mant_sn_orders_id')->with("mantRequestNeedItem");
    }

    public function ordenesEntradas() {
        return $this->hasMany(\Modules\Maintenance\Models\RequestNeedOrdersItem::class, 'mant_sn_orders_id');
    }

    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id')->with('dependencies');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function histori() {
        return $this->hasMany(\Modules\Maintenance\Models\HistoryOrder::class, 'mant_sn_orders_id')->latest();
    }

    public function getProveedorNombreAttribute(){

        $need = RequestNeed::where('id', $this->mant_sn_request_id)
        ->pluck('rubro_objeto_contrato_id')
        ->first();
        $contrato = ProviderContract::where('id', $need)
        ->pluck('mant_providers_id')
        ->first();
    
        $proveedor = Providers::where('id', $contrato)->first();

        if(!empty($proveedor)){
            return $proveedor["document_type"] . ' - ' . $proveedor["identification_rep"] . ' - ' . $proveedor["name"] . ' - ' . $proveedor["mail"];
        }

        return "";

    }

    public function getProveedorIdAttribute(){

        $need = RequestNeed::where('id', $this->mant_sn_request_id)
        ->pluck('rubro_objeto_contrato_id')
        ->first();
    
        $contrato = ProviderContract::where('id', $need)
        ->pluck('mant_providers_id')
        ->first();
    
        $proveedor = Providers::where('id', $contrato)->first();

        return !empty($proveedor) ? $proveedor->id : "";
    }




    
   public function getNumeroContratoAttribute()
    {
        // Primero verifica que solicitudPrincipal exista, y luego que contratoDatos exista.
        if ($this->solicitudPrincipal && $this->solicitudPrincipal->contratoDatos) {
            return $this->solicitudPrincipal->contratoDatos->contract_number;
        }

        return "";
    }
    /**
     * Encripta el id de la orden
     * @return
     */
    public function getEncryptedIdAttribute() {

        return  base64_encode($this->id);
    }


    // Dentro de tu modelo Order.php (o el modelo principal)


    /**
     * Obtiene todas las adiciones aprobadas de repuestos y actividades asociadas a la orden
     */
    
    // public function getAdditionsSparePartActivitiesApprovedAttribute() {
    //     $additions_id = AdditionSparePartActivity::where('order_id', $this->id)
    //         ->pluck('id')
    //         ->toArray();

    //     $additions_needs = AdditionNeed::whereIn('addition_id', $additions_id)
    //         ->where('is_approved',1)
    //         ->get()
    //         ->toArray();

    //     return $additions_needs;
    // }

    // En tu modelo AdditionNeed.php debe existir esta relación:
    public function addition()
    {
        return $this->belongsTo(AdditionSparePartActivity::class, 'addition_id');
    }


    // Luego, tu accesor en el modelo Order.php se vería así:
    public function getAdditionsSparePartActivitiesApprovedAttribute()
    {
        // Busca en AdditionNeed donde 'is_approved' es 1 Y donde la relación 'addition'
        // tiene un 'order_id' que coincide con el 'id' de esta orden.
        return AdditionNeed::where('is_approved', 1)
            ->whereHas('addition', function ($query) {
                $query->where('order_id', $this->id);
            })
            ->get();
    }

    // Dentro de tu modelo Order.php

    /**
     * Define la relación con SÓLO las adiciones que están "En trámite".
     */
    public function processingAdditions()
    {
        return $this->hasMany(AdditionSparePartActivity::class, 'order_id')
                ->whereIn('status', ['En trámite', 'En trámite devuelto','Solicitud en asignación de costo']);

    }

    // Dentro de tu modelo Order.php

    /**
     * OBTIENE la colección de adiciones en trámite como un atributo.
     * Esto te permite hacer $order->processing_additions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProcessingAdditionsAttribute()
    {
        // Llama a la relación y ejecuta la consulta con ->get()
        return $this->processingAdditions()->get();
    }
    
}
