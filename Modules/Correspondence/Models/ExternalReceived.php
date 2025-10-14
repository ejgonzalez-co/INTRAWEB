<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;
use DateTimeInterface;
USE Modules\PQRS\Models\PQR;
USE Modules\Correspondence\Models\CorreoIntegrado;
use Illuminate\Support\Facades\Auth;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;
use Modules\ExpedientesElectronicos\Models\Expediente;

/**
 * Class ExternalReceived
 * @package Modules\Correspondence\Models
 * @version January 13, 2022, 12:11 pm -05
 *
 * @property Modules\Correspondence\Models\TypesDocumentary $typeDocumentary
 * @property Modules\Correspondence\Models\Dependencia $dependency
 * @property \App\User $functionary
 * @property \Illuminate\Database\Eloquent\Collection $externalReceivedAnnotations2022s
 * @property \Illuminate\Database\Eloquent\Collection $externalReceivedAttachments2022s
 * @property \Illuminate\Database\Eloquent\Collection $externalReceivedCopyShare2022s
 * @property integer $dependency_id
 * @property integer $functionary_id
 * @property integer $citizen_id
 * @property integer $type_documentary_id
 * @property string $dependency_name
 * @property string $functionary_name
 * @property string $citizen_name
 * @property string $user_name
 * @property string $consecutive
 * @property string $issue
 * @property integer $folio
 * @property integer $annexed
 * @property integer $channel
 * @property string $novelty
 * @property string $pqr
 * @property integer $receiving_channel
 * @property string $document_pdf
 * @property string $attached_document
 * @property integer $state
 */
class ExternalReceived extends Model
{
        use SoftDeletes;

    public $table = 'external_received';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $fillable = [
        'dependency_id',
        'functionary_id',
        'citizen_id',
        'type_documentary_id',
        'dependency_name',
        'functionary_name',
        'citizen_name',
        'citizen_document',
        'citizen_email',
        'citizen_users_id',
        'user_name',
        'consecutive',
        'issue',
        'folio',
        'annexed',
        'channel',
        'novelty',
        'observation',
        'pqr',
        'receiving_channel',
        'document_pdf',
        'attached_document',
        'users_copies',
        'users_shares',
        'year',
        'state',
        'physical_address',
        'npqr',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'validation_code',
        'code_temporal',
        'recibido_fisico',
        'consecutive_order',
        'respuesta_correo',
        'reason_return'


    ];

    protected $appends = [
        'type_documentary_name',
        'channel_name',
        'state_name',
        'is_functionary',
        'user_share',
        'pqr_datos',
        'recibida_pqr_encrypted_id',
        'correo_integrado_encrypted_id',
        'correo_integrado_datos',
        'leido',
        'classification_production_office_pqr',
        'classification_serie_pqr',
        'classification_subserie_pqr',
        'count_rebounds',
        'tiene_expediente'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dependency_id' => 'integer',
        'functionary_id' => 'integer',
        'citizen_id' => 'integer',
        'type_documentary_id' => 'integer',
        'dependency_name' => 'string',
        'functionary_name' => 'string',
        'citizen_name' => 'string',
        'user_name' => 'string',
        'consecutive' => 'string',
        'issue' => 'string',
        'folio' => 'integer',
        'annexed' => 'string',
        'channel' => 'integer',
        'novelty' => 'string',
        'pqr' => 'string',
        'receiving_channel' => 'integer',
        'users_copies' => 'string',
        'users_shares' => 'string',
        'year' => 'integer',
        'state' => 'integer',
        'physical_address' => 'string',
        'classification_serie'=> 'integer',
        'classification_subserie'=> 'integer',
        'classification_production_office'=> 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'dependency_id' => 'nullable',
        'functionary_id' => 'nullable',
        'citizen_id' => 'nullable',
        'type_documentary_id' => 'nullable',
        'dependency_name' => 'nullable|string|max:120',
        'functionary_name' => 'nullable|string|max:120',
        'citizen_name' => 'nullable|string|max:120',
        'user_name' => 'nullable|string|max:120',
        'consecutive' => 'nullable|string|max:191',
        'issue' => 'nullable|string',
        'folio' => 'nullable|integer',
        'channel' => 'nullable|integer',
        'novelty' => 'nullable|string',
        'pqr' => 'nullable|string|max:45',
        'receiving_channel' => 'nullable|integer',
        'state' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


    //Contador de rebotes relacionados a la correspondencia
    public function getCountReboundsAttribute() {
        if (Auth::user()) {
            $count_notifications = NotificacionesMailIntraweb::where('consecutivo', $this->consecutive)
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
     * Append para validar si un usuario ya ha leído una correspondencia recibida
     *
     * @return void
     */
    public function getLeidoAttribute() {
        // Retorna solo el registro de leido-destacado del usuario en sesión de cada PQR
        $leido = $this->externalRead->filter(function($value, $key) {
            return $value["users_id"] == (Auth::user() ? Auth::user()->id : null);
        })->first();
        // Retorna el valor de la propiedad destacado del registro obtenido anteriormente
        return $leido ? $leido->access : null;
    }

    /**
     * Append para obtener la oficina productora de la pqr
     *
     * @return void
     */
    public function getClassificationProductionOfficePqrAttribute() {
        if (isset($this->pqr)) {
            $pqr = PQR::where('pqr_id',$this->pqr)->first();
            return !empty($pqr->classification_production_office) ?  $pqr->classification_production_office : NULL;
        } else {
            return null;
        }
    }


     /**
     * Append para obtener la oficina productora de la pqr
     *
     * @return void
     */
    public function getClassificationSeriePqrAttribute() {
        if (isset($this->pqr)) {
            $pqr = PQR::where('pqr_id',$this->pqr)->first();
            return !empty($pqr->classification_serie) ? $pqr->classification_serie : NULL;
        } else {
            return null;
        }
     }

        /**
     * Append para obtener la oficina productora de la pqr
     *
     * @return void
     */
    public function getClassificationSubseriePqrAttribute() {
        if (isset($this->pqr)) {
            $pqr = PQR::where('pqr_id',$this->pqr)->first();
            return !empty($pqr->classification_subserie) ? $pqr->classification_subserie : NULL;
        } else {
            return null;
        }
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
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function typeDocumentary() {
        return $this->belongsTo(\Modules\Correspondence\Models\TypeDocumentary::class, 'type_documentary_id');
    }

    /**
     * Append de permiso para ve la accion
     *
     * @return void
     */
    public function getRecibidaPqrEncryptedIdAttribute() {
        return ["recibida_id" => encrypt($this->id), "pqr_id" => base64_encode($this->pqr_datos ? $this->pqr_datos->id : null)];
    }

    /**
     * Append de permiso para ve la accion
     *
     * @return void
     */
    public function getUserShareAttribute() {

        if ($this->users_shares != NULL) {
            return $this->users_shares;
        }else{
            return null;
        }


    }

/**
     * Append de permiso para ve la accion
     *
     * @return void
     */
    public function getIsFunctionaryAttribute() {

        if ($this->functionary_id == Auth::id()) {
            return true;
        }else{
            return false;
        }


    }

    /**
     * Append de permiso para ve la accion
     *
     * @return void
     */
    public function getPqrDatosAttribute() {

       if ($this->pqr) {
        $pqr = PQR::where('pqr_id',$this->pqr)->first();
        return  $pqr;
       }

      return NULL;
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencies() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function functionaries() {
        return $this->belongsTo(\App\User::class, 'functionary_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalAnnotations() {
        return $this->hasMany(\Modules\Correspondence\Models\ReceivedAnnotation::class, 'external_received_id')->with('users')->latest();
    }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  **/
    // public function externalCopyShares() {
    //     return $this->hasMany(\Modules\Correspondence\Models\ExternalReceivedCopyShare::class, 'external_received_id');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  **/
    // public function externalReceivedCopy() {
    //     return $this->hasMany(\Modules\Correspondence\Models\ExternalReceivedCopyShare::class, 'external_received_id')->where("type", "=", "Copia");
    // }


    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  **/
    // public function externalReceivedShares() {
    //     return $this->hasMany(\Modules\Correspondence\Models\ExternalReceivedCopyShare::class, 'external_received_id')->where("type", "=", "Compartida");
    // }



     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalCopyShares() {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalReceivedCopyShare::class, 'external_received_id');
    }
    public function externalCopy() {
        return $this->externalCopyShares()->where('type','=', 'Copia');
    }

    public function externalShares() {
        return $this->externalCopyShares()->where('type','=', 'Compartida');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function citizens() {
        return $this->belongsTo(\Modules\Intranet\Models\Citizen::class, 'citizen_id');
    }



      /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalRead()
    {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalReceivedRead::class, 'correspondence_external_id');
    }

       /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalHistory()
    {
        return $this->hasMany(\Modules\Correspondence\Models\ReceivedHistory::class, 'external_received_id')->with('users');
    }


    /**
     * Obtiene el nombre del tipo de documento
     * @return
     */
    public function getTypeDocumentaryNameAttribute() {
        // Valida si el dato es diferente de vacio
        if (!empty($this->type_documentary_id)) {
            return $this->typeDocumentary->name ?? 'No definido';
        }
        return null;
    }

    /**
     * Obtiene el nombre del tipo de documento
     * @return
     */
    public function getChannelNameAttribute() {
        // Valida si el dato es diferente de vacio
        if (!empty($this->channel)) {
            return AppBaseController::getObjectOfList(config('correspondence.external_received_channels'), 'id', $this->channel)->name ?? 'Sin canal';
        }
        return null;
    }

    /**
     * Obtiene el nombre del estado
     * @return
     */
    public function getStateNameAttribute() {
        // Valida si el dato es diferente de vacio
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('correspondence.external_received_states'), 'id', $this->state)->name;
        }
        return null;
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

    public function pqrDatos()
    {
        return $this->belongsTo(\Modules\Pqrs\Models\Pqr::class, 'pqr', 'pqr_id');
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
        return $this->hasMany(ReceivedAnnotation::class, 'external_received_id')
                    // ->whereRaw("NOT (leido_por LIKE '%{$userId}%')");
            ->where('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
            ->Where('leido_por', 'not like', $userId . ',%')
            ->where('leido_por', 'not like', '%,' . $userId)
            ->Where('leido_por', 'not like', $userId );
    }

    /**
     * Append para encriptar el correo integrado
     *
     * @return void
     */
    public function getcorreoIntegradoEncryptedIdAttribute() {
        return ["recibida_id" => encrypt($this->id), "correo_id" => base64_encode($this->correo_integrado_datos ? $this->correo_integrado_datos->consecutivo : null)];
    }

    /**
     * Append de permiso para ve la accion
     *
     * @return void
     */
    public function getcorreoIntegradoDatosAttribute() {

        if ($this->id) {
            $correo_integrado = CorreoIntegrado::with("adjuntosCorreo")->where('external_received_id',$this->id)->first();
            if($correo_integrado) {
                $correo = $correo_integrado->toArray();
                $soloAdjuntos = array_column($correo["adjuntos_correo"], 'adjunto');
                $correo_integrado["adjuntos_correo_cadena"] = implode(',', $soloAdjuntos);
            }
            return  $correo_integrado;
        }

       return NULL;
     }


    /**
     * Valida si tiene asociado un expedinete
     * @return
     */
    public function getTieneExpedienteAttribute() {
        if($this->state == "3") {
            $expediente_info = Expediente::selectRaw("id, consecutivo, id_responsable, nombre_expediente, TO_BASE64(id) AS id_encoded")
                ->whereHas("eeDocumentosExpedientes", function($query) {
                    $query->where("modulo_consecutivo", $this->consecutive);
                })
                ->get()->toArray();
            return $expediente_info ?? null;
        }
    }

}
