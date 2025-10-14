<?php

namespace Modules\PQRS\Models;

use \App\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
USE Modules\Correspondence\Models\ExternalReceived;
USE Modules\Correspondence\Models\External;
use DB;
use Modules\ExpedientesElectronicos\Models\Expediente;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;

class PQR extends Model
{

    public $table = 'pqr';

    public $fillable = [
        'pqr_id',
        'nombre_ciudadano',
        'documento_ciudadano',
        'email_ciudadano',
        'document_pdf',
        'adjunto_ciudadano',
        'contenido',
        'folios',
        'anexos',
        'canal',
        'respuesta',
        'adjunto',
        'descripcion_tramite',
        'devolucion',
        'operador',
        'operador_name',
        'fecha_recibido_fisico',
        'estado',
        'nombre_ejetematico',
        'plazo',
        'tipo_plazo',
        'temprana',
        'destacado',
        'no_oficio_respuesta',
        'adj_oficio_respuesta',
        'no_oficio_solicitud',
        'adj_oficio_solicitud',
        'tipo_finalizacion',
        'tipo_adjunto',
        'correspondence_external_received_id',
        'correspondence_external_id',
        'fecha_fin_parcial',
        'respuesta_parcial',
        'adjunto_r_parcial',
        'adjunto_r_ciudadano',
        'fecha_vence',
        'fecha_fin',
        'fecha_temprana',
        'funcionario_destinatario',
        'pregunta_ciudadano',
        'respuesta_ciudadano',
        'empresa_traslado',
        'tipo_solicitud_nombre',
        'vigencia',
        'users_id',
        'funcionario_users_id',
        'ciudadano_users_id',
        'pqr_eje_tematico_id',
        'pqr_tipo_solicitud_id',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'users_name',
        'adjunto_finalizado',
        'dias_restantes',
        'validation_code',
        'id_correspondence',
        'adjunto_correspondence',
        'dependency_id',
        'adjunto_espera_ciudadano',
        'no_matricula',
        'direccion_predio',
        'motivos_hechos',
        'respuesta_correo',
        'de_documento_id'


    ];

    protected $casts = [
        'pqr_id' => 'string',
        'nombre_ciudadano' => 'string',
        'funcionario_users_id' => 'int',
        'classification_subserie' => 'int',
        'classification_serie' => 'int',
        'classification_production_office' => 'int',
        'document_pdf' => 'string',
        'documento_ciudadano' => 'string',
        'email_ciudadano' => 'string',
        'contenido' => 'string',
        'anexos' => 'string',
        'canal' => 'string',
        'respuesta' => 'string',
        'descripcion_tramite' => 'string',
        'devolucion' => 'string',
        'fecha_recibido_fisico' => 'datetime',
        'estado' => 'string',
        'nombre_ejetematico' => 'string',
        'tipo_plazo' => 'string',
        'destacado' => 'string',
        'no_oficio_respuesta' => 'string',
        'adj_oficio_respuesta' => 'string',
        'no_oficio_solicitud' => 'string',
        'adj_oficio_solicitud' => 'string',
        'tipo_finalizacion' => 'string',
        'tipo_adjunto' => 'string',
        'fecha_fin_parcial' => 'date',
        'respuesta_parcial' => 'string',
        'adjunto_r_parcial' => 'string',
        'adjunto_r_ciudadano' => 'string',
        'empresa_traslado' => 'string',
        'tipo_solicitud_nombre' => 'string',
        'fecha_vence' => 'datetime',
        'fecha_fin' => 'datetime',
        'fecha_temprana' => 'datetime',
        'funcionario_destinatario' => 'string',
        'pregunta_ciudadano' => 'string',
        'respuesta_ciudadano' => 'string',
        'adjunto_finalizado' => 'string',
        'dias_restantes'=>'string',
        'id_correspondence'=>'string',
        'adjunto_correspondence'=>'string',
        'respuesta_correo' => 'integer'
    ];

    public static array $rules = [
        'pqr_id' => 'nullable|string|max:45',
        'nombre_ciudadano' => 'nullable|string|max:255',
        'documento_ciudadano' => 'nullable|string|max:255',
        'email_ciudadano' => 'nullable|string|max:255',
        'contenido' => 'nullable|string',
        'folios' => 'nullable',
        'anexos' => 'nullable|string|max:255',
        'canal' => 'nullable|string|max:150',
        'respuesta' => 'nullable|string',
        'descripcion_tramite' => 'nullable|string',
        'devolucion' => 'nullable|string',
        'operador' => 'nullable',
        'fecha_recibido_fisico' => 'nullable',
        'estado' => 'required|string|max:200',
        'nombre_ejetematico' => 'nullable|string|max:200',
        'plazo' => 'nullable',
        'tipo_plazo' => 'nullable|string|max:50',
        'temprana' => 'nullable',
        'destacado' => 'nullable|string|max:16777215',
        'no_oficio_respuesta' => 'nullable|string|max:100',
        'no_oficio_solicitud' => 'nullable|string|max:100',
        'tipo_finalizacion' => 'nullable|string|max:255',
        'tipo_adjunto' => 'nullable|string|max:255',
        'correspondence_external_received_id' => 'nullable',
        'correspondence_external_id' => 'nullable',
        'fecha_fin_parcial' => 'nullable',
        'respuesta_parcial' => 'nullable|string',
        'fecha_vence' => 'nullable',
        'fecha_fin' => 'nullable',
        'fecha_temprana' => 'nullable',
        'pregunta_ciudadano' => 'nullable|string',
        'respuesta_ciudadano' => 'nullable|string',
        'empresa_traslado' => 'nullable|string|max:250',
        'tipo_solicitud_nombre' => 'nullable|string|max:200',
        'vigencia' => 'nullable',
        'ciudadano_users_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];


    protected $appends = ['destacado', 'leido', 'linea_tiempo', 'validar_copia_destinatario', 'permission_edit', 'recibida_encrypted_id','external_encrypted_id','dependencia_funcionario','estado_actual','count_rebounds','tiene_expediente','documento_electronico_encrypted_id'];


    //Contador de rebotes relacionados a la correspondencia
    public function getCountReboundsAttribute() {
        if (Auth::user()) {
            $count_notifications = NotificacionesMailIntraweb::where('consecutivo', $this->pqr_id)
            ->where(function($query) {
                $query->where('estado_notificacion', 'Rebote')
                    ->orWhere('estado_notificacion', 'No entregado');
            })
            ->where('user_id', Auth::user()->id)  // Añadir condición para user_id
            ->count();
        }else{
            $count_notifications = 0;
        }

        return $count_notifications;
    }

     /**
     * Append que muestra si ya se cargo un adjunto o no
     *
     * @return void
     */
    public function getAdjuntoVisibleAttribute() {

     return isset($this->adjunto)  ? true : false ;
    }


    /**
     * Append que muestra el estado actual
     *
     * @return void
     */
    public function getEstadoActualAttribute() {
        return $this->estado;
    }

    /**
     * Append que muestra la dependencia del funcionario asignada
     *
     * @return void
     */
    public function getDependenciaFuncionarioAttribute() {

        if (  isset($this->funcionario_users_id) and $this->funcionario_users_id != 0 ) {

            $funcionario_destinatario = User::find($this->funcionario_users_id);
            // Verifica si $funcionario_destinatario está vacío
            if(empty($funcionario_destinatario)) {
                // Si está vacío, asigna "sin dependencia" a $dependencia
                $dependencia = "Sin dependencia";
            } else {
                // Si no está vacío, realiza una consulta a la base de datos
                // para obtener la dependencia asociada al id del destinatario
                $dependencia = DB::table('dependencias')
                                ->where('id', $funcionario_destinatario->id_dependencia)
                                ->first();

                // Extrae el nombre de la dependencia del resultado de la consulta
                $dependencia = $dependencia->nombre ?? 'Sin dependencia';
            }
            return  $dependencia;
        }
        return ;
    }

    /**
     * Append que valida si los registros a listar van dirijidos con copia al usuario en sesión
     *
     * @return void
     */
    public function getValidarCopiaDestinatarioAttribute() {
        // Retorna solo el registro de copia al usuario en sesión de cada PQR
        $copia_destinatario = $this->pqrCopia->filter(function($value, $key) {
            return $value["users_id"] == (Auth::user() ? Auth::user()->id : null);
        })->first();
        // Retorna el valor del id del usuario con copia del registro obtenido anteriormente
        return $copia_destinatario ? $copia_destinatario->users_id : null;
    }

    /**
     * Genera la consulta para filtrar los registros simulando por el campo linea_tiempo
     *
     * @param [type] $query Consulta que se esta generando
     * @param [type] $value Linea de tiempo a filtrar
     * @return void
     */
    public function scopeWhereLineaTiempo($query, $value) {
        // Valida si se recibe un valor para el campo linea de tiempo
        if($value) {
            // $query->where(DB::raw("IF(fecha_fin > fecha_vence || (NOW() > fecha_vence && fecha_fin IS NULL), 'Vencido', IF(NOW() >= fecha_temprana && estado != 'Finalizado' && estado != 'Finalizado vencido justificado' && estado != 'Abierto' && estado != 'Cancelado', 'Próximo a vencer', 'A tiempo'))"), $value)->whereNot("estado", "Abierto")->whereNot("estado", "Finalizado")->whereNot("estado", "Finalizado vencido justificado")->whereNot("estado", "Cancelado");
            $query->where(DB::raw("IF(fecha_fin > fecha_vence || (CURRENT_TIMESTAMP() > fecha_vence && fecha_fin IS NULL), 'Vencido', IF(CURRENT_TIMESTAMP() >= fecha_temprana && estado != 'Finalizado' && estado != 'Finalizado vencido justificado' && estado != 'Abierto' && estado != 'Cancelado', 'Próximo a vencer', IF(estado != 'Abierto' && estado != 'Cancelado', 'A tiempo', 'con cierre')))"), $value);
        }
    }

    /**
     * Append que calcula la categoría o línea de tiempo de los PQRS
     *
     * @return void
     */
    public function getLineaTiempoAttribute() {
        // Valida el estado del PQR, si es Abierto o Cancelado, no tiene línea de tiempo
        if($this->estado == "Abierto" || $this->estado == "Cancelado" || $this->estado == "Finalizado vencido justificado") {
            return null;
        } else {
            // Valida si la fecha de finalización o la fecha actual es mayor a la fecha de vencimiento, de ser así, el PQR esta vencido
            // if($this->fecha_fin > $this->fecha_vence || (date("Y-m-d") > date('Y-m-d', strtotime($this->fecha_vence)) && !$this->fecha_fin)) {
            //     $linea_tiempo = "Vencido";
            // // Valida si la fecha de finalización o la fecha_temprana
            // } else if(date("Y-m-d") >= date('Y-m-d', strtotime($this->fecha_temprana)) && $this->estado != "Finalizado") {
            //     $linea_tiempo = "Próximo a vencer";
            // } else {
            //     $linea_tiempo = "A tiempo";
            // }

            // return $linea_tiempo;

            // Valida si la fecha de finalización o la fecha actual es mayor a la fecha de vencimiento, de ser así, el PQR está vencido
                if ($this->fecha_fin > $this->fecha_vence || (date("Y-m-d H:i:s") > $this->fecha_vence && !$this->fecha_fin)) {
                    $linea_tiempo = "Vencido";
                // Valida si la fecha actual es mayor o igual a la fecha_temprana
                } else if (date("Y-m-d H:i:s") >= $this->fecha_temprana && $this->estado != "Finalizado") {
                    $linea_tiempo = "Próximo a vencer";
                } else {
                    $linea_tiempo = "A tiempo";
                }

                return $linea_tiempo;

        }
    }

    /**
     * Append para validar si un usuario ya ha leído un PQR
     *
     * @return void
     */
    public function getLeidoAttribute() {
        // Retorna solo el registro de leido-destacado del usuario en sesión de cada PQR
        $leido = $this->pqrLeidos->filter(function($value, $key) {
            return $value["users_id"] == (Auth::user() ? Auth::user()->id : null);
        })->first();
        // Retorna el valor de la propiedad destacado del registro obtenido anteriormente
        return $leido ? $leido->accesos : null;
    }

    /**
     * Append para validar si un usuario ha destacado un PQR
     *
     * @return void
     */
    public function getDestacadoAttribute() {
        // Retorna solo el registro de leido-destacado del usuario en sesión de cada PQR
        $destacado = $this->pqrLeidos->filter(function($value, $key) {
            return $value["users_id"] == (Auth::user() ? Auth::user()->id : null);
        })->first();
        // Retorna el valor de la propiedad destacado del registro obtenido anteriormente
        return $destacado ? $destacado->destacado : null;
    }

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

    public function pqrCorrespondence(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\ExternalReceived::class, 'correspondence_external_received_id');
    }

    public function pqrCorrespondenceExternal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\External::class, 'correspondence_external_id');
    }

    public function pqrHistorial(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQRHistorial::class, 'pqr_pqr_id')->with(["users", "pqrEjeTematico"]);
    }

    public function pqrEjeTematico(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQREjeTematico::class, 'pqr_eje_tematico_id')->with(["pqrEjeTematicoHistorial"]);
    }

    public function pqrTipoSolicitud(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQRTipoSolicitud::class, 'pqr_tipo_solicitud_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function funcionarioUsers(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'funcionario_users_id')->with(["dependencies"]);
    }

    public function ciudadanoUsers(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'ciudadano_users_id');
    }

    public function pqrAnotacions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQRAnotacion::class, 'pqr_id')->with(["users"])->latest();
    }

    public function pqrCopiaCopmpartida(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQRCopia::class, 'pqr_id')->with(["users"]);
    }

    public function pqrCopia(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->pqrCopiaCopmpartida()->where('tipo','=', 'Copia');
    }

    public function pqrCompartida(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->pqrCopiaCopmpartida()->where('tipo','=', 'Compartida');
    }

    public function pqrLeidos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQRLeido::class, 'pqr_id')->orderBy("created_at", "DESC");
    }

    public function encuesta(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\SurveySatisfactionPqr::class, 'pqr_id')->orderBy("created_at", "DESC");
    }

    /**
     * Relación con la serie de la clasificación documental
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function serieClasificacionDocumental() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\seriesSubSeries::class, 'classification_serie');
    }

    /**
     * Relación con la subserie de la clasificación documental
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subserieClasificacionDocumental() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\seriesSubSeries::class, 'classification_subserie');
    }

    /**
     * Relación con la oficina productora de la clasificación documental
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function oficinaProductoraClasificacionDocumental() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'classification_production_office');
    }


     /**
     * Devuelve las anotaciones pendientes para el usuario actual.
     *
     * Este método define una relación para recuperar las anotaciones pendientes
     * asociadas a esta instancia de modelo para el usuario actual.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function anotacionesPendientes()
    {
        // Obtiene el ID del usuario actual autenticado
        $userId = Auth::check() ? Auth::id() : 0;

        // Define una relación "hasMany" para recuperar las anotaciones pendientes
        // donde el campo "leido_por" no contiene el ID del usuario actual
        // return $this->hasMany(\Modules\PQRS\Models\PQRAnotacion::class, 'pqr_id')
        //             ->whereRaw("NOT (leido_por LIKE '%{$userId}%')");


        return $this->hasMany(\Modules\PQRS\Models\PQRAnotacion::class, 'pqr_id')
        ->where('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
        ->Where('leido_por', 'not like', $userId . ',%')
        ->where('leido_por', 'not like', '%,' . $userId)
        ->Where('leido_por', 'not like', $userId );

    }


    public function getPermissionEditAttribute()
    {
        if(Auth::user() && (Auth::user()->id == $this->funcionario_users_id &&  $this->estado != "Finalizado"  &&  $this->estado != "Cancelado" &&  $this->estado != "Finalizado vencido justificado")  && !Auth::user()->hasRole('Administrador de requerimientos')) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Append de encriptar los datos
     *
     * @return void
     */
    public function getRecibidaEncryptedIdAttribute() {
        return ["recibida_id" => encrypt($this->id), "id_correspondence_recibida" => base64_encode($this->correspondence_external_received_id ?? null)];
    }

    /**
     * Append de encriptar los datos
     *
     * @return void
     */
    public function getExternalEncryptedIdAttribute() {
        return ["external_id" => encrypt($this->id), "id_correspondence_external" => base64_encode($this->correspondence_external_id ?? null)];
    }

    /**
     * Valida si tiene asociado un expedinete
     * @return
     */
    public function getTieneExpedienteAttribute() {
        if($this->estado != 'Abierto') {
            $expediente_info = Expediente::selectRaw("id, consecutivo, id_responsable, nombre_expediente, TO_BASE64(id) AS id_encoded")
                ->whereHas("eeDocumentosExpedientes", function($query) {
                    $query->where("modulo_consecutivo", $this->pqr_id);
                })
                ->get()->toArray();
            return $expediente_info ?? null;
        }
    }

    /**
     * Relación con el documento electrónico asociado a la PQR.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pqrDocumentoElectronico(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\Documento::class, 'de_documento_id');
    }

    /**
     * Atributo accesor para obtener identificadores del documento electrónico de forma segura.
     * Este método crea un atributo adicional llamado `documento_electronico_encrypted_id`
     *
     * @return array<string, string|null>  Arreglo con los IDs codificados.
     */
    public function getDocumentoElectronicoEncryptedIdAttribute()
    {
        return ["documento_id" => encrypt($this->id), "id_documento_electronico" => base64_encode($this->de_documento_id ?? null)];
    }

}
