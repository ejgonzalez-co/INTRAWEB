<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\User;
use Auth;
use DateTimeInterface;
/**
 * Class RequestNeed
 * @package Modules\Maintenance\Models
 * @version November 29, 2023, 2:54 pm -05
 *
 * @property Modules\Maintenance\Models\Dependencia $dependencias
 * @property Modules\Maintenance\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $mantSnRequestHistories
 * @property \Illuminate\Database\Eloquent\Collection $mantSnRequestNeeds
 * @property string $tipo_solicitud
 * @property string $tipo_necesidad
 * @property string $tipo_activo
 * @property integer $activo_id
 * @property string $rubro_nombre
 * @property string $rubro_id
 * @property string $rubro_objeto_contrato_id
 * @property integer $valor_disponible
 * @property string $observacion
 * @property string $estado
 * @property integer $dependencias_id
 * @property integer $users_id
 */
class RequestNeed extends Model {
    use SoftDeletes;

    public $table = 'mant_sn_request';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'tipo_solicitud',
        'mant_administration_cost_items_id',
        'tipo_necesidad',
        'tipo_activo',
        'activo_id',
        'rubro_nombre',
        'rubro_id',
        'rubro_objeto_contrato_id',
        'valor_disponible',
        'observacion',
        'estado',
        'dependencias_id',
        'users_id',
        'approving_user_id',
        'consecutivo',
        'kilometraje_horometro',
        'total_solicitud',
        'en_administracion',    
        'url_documents',
        'approval_date',
        'approval_justification',
        'invoice_no',
        'date_supervisor_submission',
        'supervisor_observation',
        'second_rubro_nombre',
        'second_rubro_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tipo_solicitud' => 'string',
        'tipo_necesidad' => 'string',
        'tipo_activo' => 'string',
        'activo_id' => 'integer',
        'rubro_nombre' => 'string',
        'rubro_id' => 'string',
        'rubro_objeto_contrato_id' => 'integer',
        'valor_disponible' => 'integer',
        'observacion' => 'string',
        'estado' => 'string',
        'dependencias_id' => 'integer',
        'users_id' => 'integer',
        'approving_user_id' => 'integer',
        'second_rubro_id' => 'integer'

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'tipo_solicitud' => 'nullable|string|max:45',
        'tipo_necesidad' => 'nullable|string|max:45',
        'tipo_activo' => 'nullable|string|max:45',
        'activo_id' => 'nullable|integer',
        'rubro_nombre' => 'nullable|string|max:45',
        'rubro_objeto_contrato_id' => 'nullable|string|max:45',
        'observacion' => 'nullable|string',
        'estado' => 'nullable|string|max:45'
    ];
    protected $appends = ["activo_nombre","id_encript", "dependencia","estado_stock_almacen","estado_proveedor_externo_almacen","numero_salida_almacen","fecha_salida_almacen","proveedor_nombre","rubro_aseo_datos",'id_combinado','heading_information', 'heading_information_second','is_visible_management_orders','pending_additions'];

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
    public function dependencia() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function approvedUserInformation() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'approving_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historial() {
        return $this->hasMany(\Modules\Maintenance\Models\RequestNeedHistory::class, 'mant_sn_request_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function necesidades() {
        return $this->hasMany(\Modules\Maintenance\Models\RequestNeedItem::class, 'mant_sn_request_id');
    }

    
    public function contratoDatos() {
        return $this->belongsTo(\Modules\Maintenance\Models\ProviderContract::class, 'rubro_objeto_contrato_id')->with('providers');
    }

        
    // public function rubroAseoDatos() {
    //     return $this->belongsTo(\Modules\Maintenance\Models\ProviderContract::class, 'rubro_objeto_contrato_id2');
    // }

    public function rubroDatos() {

        return $this->belongsTo(\Modules\Maintenance\Models\Heading::class, 'rubro_id');
    }

    public function getHeadingInformationAttribute(){
        if($this->tipo_solicitud == "Inventario"){
            $administrationCostItem = AdministrationCostItem::find($this->rubro_id);
            return !is_null($administrationCostItem) ? $administrationCostItem->toArray() : [];
        }

        if(!is_null($this->mant_administration_cost_items_id)){
            $administrationCostItem = AdministrationCostItem::find($this->mant_administration_cost_items_id);
            return !is_null($administrationCostItem) ? $administrationCostItem->toArray() : [];
        }

        return null;
    }

     public function getHeadingInformationSecondAttribute()
        {
            if (!$this->second_rubro_id) {
                return null;
            }

            // 🔹 Trae info del heading
            $rubro = DB::table('mant_sn_actives_heading')
                ->select('rubro_id', 'rubro_codigo', 'centro_costo_codigo')
                ->where('id', $this->second_rubro_id)
                ->first();

            if (!$rubro) {
                return null;
            }

            // 🔹 Trae info del administration cost item
            $name = DB::table('mant_administration_cost_items')
                ->select('name', 'code_cost', 'cost_center','cost_center_name')
                ->where('mant_heading_id', $rubro->rubro_id)
                ->where('cost_center', $rubro->centro_costo_codigo)
                ->first();

            if (!$name) {
                return null;
            }

            // 🔹 Devuelvo información armada
            return [
                'rubro_id' => $rubro->rubro_id,
                'rubro_codigo' => $rubro->rubro_codigo,
                'centro_costo_codigo' => $rubro->centro_costo_codigo,
                'name' => $name->name,
                'code_cost' => $name->code_cost,
                'cost_center' => $name->cost_center,
                'cost_center_name' => $name->cost_center_name
            ];
        }


    
    public function getRubroAseoDatosAttribute(){
        return [];
    }
    public function getActivoNombreAttribute(){

    //    return DB::table('vw_actives_complete')
    //     ->select(DB::raw('
    //         CASE
    //             WHEN plaque IS NOT NULL THEN CONCAT(name, " ", plaque)
    //             ELSE CONCAT(name, " ", code)
    //         END AS name
    //     '))
    //     ->where('mant_asset_type_id', $this->tipo_activo)
    //     ->where('item_id', $this->activo_id)
    //     ->value('name');

        return DB::table('vw_actives_complete')
        ->select(DB::raw('CONCAT(name, " ", COALESCE(plaque, code, "")) AS name'))
        ->where('mant_asset_type_id', $this->tipo_activo)
        ->where('item_id', $this->activo_id)
        ->value('name');

    }

    public function getIdEncriptAttribute(){
    
           return base64_encode($this->id);
    
        }

        public function getDependenciaAttribute(){
            if(session('outside')){
                $dependencia = Dependency::where("id",$this->dependencias_id)->first();
                return $dependencia;
            }
            if(Auth::user()){
                if(Auth::user()->hasRole("Administrador de mantenimientos")){
                    $dependencia = Dependency::where("id",$this->dependencias_id)->first();
                    return $dependencia;
                }
            }
            if (Auth::check() && session('outside') != true){
            $user = User::join('dependencias as d', 'users.id_dependencia', '=', 'd.id')
            ->where('users.id',Auth::user()->id)
            ->select('d.nombre')
            ->first();
            $dependencia =   $user->nombre; 
            return  $dependencia;
            }
            return null;
         }

    public function getEstadoStockAlmacenAttribute(){
        if($this->tipo_solicitud == "Stock" || $this->tipo_solicitud == "Inventario"){
            $requestNeedOrderStatus = RequestNeedOrders::select("tramite_almacen")->where("mant_sn_request_id",$this->id)->first();
            return $requestNeedOrderStatus->tramite_almacen ?? null;
        }
    }

    public function getEstadoProveedorExternoAlmacenAttribute(){
        if($this->tipo_solicitud == "Inventario" || $this->tipo_solicitud == "Activo"){
            $requestNeedOrderStatus = RequestNeedOrders::select("estado_proveedor")->where("mant_sn_request_id",$this->id)->first();
            return $requestNeedOrderStatus->estado_proveedor ?? null;
        }
    }

    public function getProveedorNombreAttribute(){
        if($this->tipo_solicitud == "Inventario" || $this->tipo_solicitud == "Activo"){
            $providerName = RequestNeedOrders::select("rol_asignado_nombre")->where("mant_sn_request_id",$this->id)->first();
            return $providerName->rol_asignado_nombre ?? null;
        }
    }

    public function getNumeroSalidaAlmacenAttribute(){
        if($this->tipo_solicitud == "Stock"){
            $requestNeedOrderStatus = RequestNeedOrders::select("numero_salida_almacen")->where("mant_sn_request_id",$this->id)->first();
            return $requestNeedOrderStatus->numero_salida_almacen ?? null;
        }
    }

    public function getFechaSalidaAlmacenAttribute(){
        if($this->tipo_solicitud == "Stock"){
            $requestNeedOrderStatus = RequestNeedOrders::select("fecha_entrada_almacen")->where("mant_sn_request_id",$this->id)->first();
            return $requestNeedOrderStatus->fecha_entrada_almacen ?? null;
        }
    }

    public function ordenes() {
        return $this->hasMany(\Modules\Maintenance\Models\RequestNeedOrders::class, 'mant_sn_request_id');
    }

    public function getIdCombinadoAttribute() : string {
        $administrationCostItem = AdministrationCostItem::find($this->mant_administration_cost_items_id);

        $administrationCostItem = is_array($administrationCostItem) ? $administrationCostItem : ["cost_center_name" => ""];

        return $this->rubro_id . "-{$administrationCostItem['cost_center_name']}";
    }

    public function getIsVisibleManagementOrdersAttribute() : bool {
        $userCreatorIsAdmin = User::where("id",$this->users_id)->role("Administrador de mantenimientos")->count();

        return $userCreatorIsAdmin === 0;
    }

    public function getRubroIdAttribute()
    {
        $valor = $this->attributes['rubro_id'] ?? null;

        if (is_null($valor)) {
            return null;
        }

        // Devuelve como integer si es estrictamente numérico, sino como string
        return preg_match('/^\d+$/', $valor) ? (int) $valor : (string) $valor;
    }

    public function processingAdditions() {
        return $this->hasMany(\Modules\Maintenance\Models\AdditionSparePartActivity::class, 'request_id')
                        ->whereIn('status', ['En trámite', 'En trámite devuelto','Solicitud en asignación de costo']);

    }   

    // Coloca esto en tu modelo Modules\Maintenance\Models\RequestNeed.php

    /**
     * Accesor que obtiene la colección de adiciones en trámite.
     * Usa la relación 'processingAdditions' que ya está filtrada.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingAdditionsAttribute()
    {
        // Al acceder a "processingAdditions" como una propiedad,
        // Eloquent ejecuta la relación y devuelve la colección de resultados.
        return $this->processingAdditions;
    }
    

}
