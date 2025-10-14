<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;
use Illuminate\Support\Facades\Auth;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;
use Modules\ExpedientesElectronicos\Models\Expediente;
use DB;
/**
 * Class Internal
 * @package Modules\Correspondence\Models
 * @version January 19, 2022, 3:53 pm -05
 *
 * @property Modules\Correspondence\Models\CorrespondenceInternalType $type
 * @property Modules\Correspondence\Models\Dependencia $dependencias
 * @property Modules\Intranet\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceInternalAnnotations
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceInternalCopyShares
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceInternalRecipients
 * @property string $consecutive
 * @property string $state
 * @property string $title
 * @property string $content
 * @property string $folios
 * @property string $annexes
 * @property string $annexes_description
 * @property string $type_document
 * @property string $require_answer
 * @property string $answer_consecutive
 * @property string $template
 * @property string $editor
 * @property string $origen
 * @property string $recipients
 * @property string $document
 * @property string $document_pdf
 * @property string $from
 * @property string $dependency_from
 * @property string $elaborated
 * @property string $reviewd
 * @property string $approved
 * @property string $elaborated_names
 * @property string $reviewd_names
 * @property string $approved_names
 * @property string $creator_name
 * @property string $creator_dependency_name
 * @property string $elaborated_now
 * @property string $reviewd_now
 * @property string $approved_now
 * @property integer $number_review
 * @property string $observation
 * @property string $times_read
 * @property string $user_from_last_update
 * @property string $user_for_last_update
 * @property integer $type
 * @property integer $users_id
 * @property integer $dependencias_id
 */
class Internal extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_internal';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'consecutive',
        'state',
        'title',
        'content',
        'folios',
        'annexes',
        'annexes_description',
        'type_document',
        'require_answer',
        'answer_consecutive',
        'template',
        'editor',
        'origen',
        'recipients',
        'document',
        'document_pdf',
        'from',
        'dependency_from',
        'elaborated',
        'reviewd',
        'approved',
        'elaborated_names',
        'reviewd_names',
        'approved_names',
        'creator_name',
        'creator_dependency_name',
        'elaborated_now',
        'reviewd_now',
        'approved_now',
        'number_review',
        'observation',
        'times_read',
        'user_from_last_update',
        'user_for_last_update',
        'type',
        'users_id',
        'users_name',
        'dependencias_id',
        'from_id',
        'year',
        'consecutive_order',
        'internal_all',
        'copies',
        'answer_consecutive_name',
        'annexes_digital',
        'hash_document_pdf',
        'validation_code',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'firma_desde_componente',
        'fecha_limite_respuesta',
        'responsable_respuesta',
        'responsable_respuesta_nombre',
        'estado_respuesta',
        'template_preview'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'consecutive' => 'string',
        'state' => 'string',
        'title' => 'string',
        'content' => 'string',
        'folios' => 'string',
        'annexes' => 'string',
        'annexes_description' => 'string',
        'type_document' => 'string',
        'require_answer' => 'string',
        'answer_consecutive' => 'string',
        'template' => 'string',
        'template_preview' => 'string',
        'editor' => 'string',
        'origen' => 'string',
        'recipients' => 'string',
        'document' => 'string',
        'from' => 'string',
        'dependency_from' => 'string',
        'elaborated' => 'string',
        'reviewd' => 'string',
        'approved' => 'string',
        'elaborated_names' => 'string',
        'reviewd_names' => 'string',
        'approved_names' => 'string',
        'creator_name' => 'string',
        'creator_dependency_name' => 'string',
        'elaborated_now' => 'string',
        'reviewd_now' => 'string',
        'approved_now' => 'string',
        'number_review' => 'integer',
        'observation' => 'string',
        'times_read' => 'string',
        'user_from_last_update' => 'string',
        'user_for_last_update' => 'string',
        'type' => 'integer',
        'users_id' => 'integer',
        'users_name' => 'string',
        'dependencias_id' => 'integer',
        'year' => 'integer',
        'created_at'=>'string',
        'classification_serie'=> 'integer',
        'classification_subserie'=> 'integer',
        'classification_production_office'=> 'integer',
        'responsable_respuesta'=> 'integer'

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
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'consecutive' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:45',
        'title' => 'nullable|string',
        'content' => 'nullable|string',
        'folios' => 'nullable|string|max:255',
        'annexes' => 'nullable|string|max:255',
        'annexes_description' => 'nullable|string',
        'type_document' => 'nullable|string|max:255',
        'answer_consecutive' => 'nullable|string|max:255',
        'template' => 'nullable|string|max:255',
        'editor' => 'nullable|string|max:255',
        'origen' => 'nullable|string|max:45',
        'recipients' => 'nullable|string',
        'document' => 'nullable|string',
        'from' => 'nullable|string|max:255',
        'dependency_from' => 'nullable|string|max:255',
        'elaborated' => 'nullable|string|max:255',
        'reviewd' => 'nullable|string|max:255',
        'approved' => 'nullable|string|max:255',
        'elaborated_names' => 'nullable|string',
        'reviewd_names' => 'nullable|string',
        'approved_names' => 'nullable|string',
        'creator_name' => 'nullable|string|max:255',
        'creator_dependency_name' => 'nullable|string|max:255',
        'elaborated_now' => 'nullable|string|max:255',
        'reviewd_now' => 'nullable|string|max:255',
        'approved_now' => 'nullable|string|max:255',
        'number_review' => 'nullable|integer',
        'observation' => 'nullable|string',
        'times_read' => 'nullable|string',
        'user_from_last_update' => 'nullable|string|max:1000',
        'user_for_last_update' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


    protected $appends = [
        'query',
        'permission_edit',
        'list_recipients',
        'observation_inicial',
        'count_rebounds',
        'leido',
        'is_overdue',
        'data_interna_relacionada',
        'tiene_expediente',
        'permission_check',
        'status_permission_check'
    ];

    public function getStatusPermissionCheckAttribute()
    {
        $authUserId = Auth::user() ? Auth::user()->id : null;
        //Verificar si el usuario tiene un registro en internalChequeos para esta interna
        $chequeo = DB::table('correspondence_internal_chequeos')
        ->where('correspondence_internal_id', $this->id)
        ->where('users_id', $authUserId)
        ->first();

        // Si hay un registro en internalChequeos, devolver su estado (Si/No)
        if ($chequeo) {
            return $chequeo->estado_check; // Retorna "Si" o "No" según la base de datos
        }
    }

    public function getPermissionCheckAttribute()
    {
        $authUserId = (Auth::user() ? Auth::user()->id : null);
        $dependenciaId = (Auth::user() ? Auth::user()->id_dependencia : null);
        $cargoId = (Auth::user() ? Auth::user()->id_cargo : null);
    
        // Obtener los grupos de trabajo del usuario autenticado
        $groups = DB::table('users_work_groups')
            ->where('users_id', $authUserId)
            ->pluck("work_groups_id")
            ->toArray();
    
        // Verificar si el usuario está relacionado en internalRecipients
        $exists = DB::table('correspondence_internal_recipient')
        ->where('correspondence_internal_id', $this->id) // Filtrar por la relación actual
        ->where(function ($query) use ($authUserId, $dependenciaId, $cargoId, $groups) {
            $query->where('users_id', $authUserId)
                ->orWhere('dependencias_id', $dependenciaId)
                ->orWhere('cargos_id', $cargoId);

            if (!empty($groups)) {
                $query->orWhereIn('work_groups_id', $groups);
            }
        })
        ->exists();

        $exists1 = DB::table('correspondence_internal_copy_share')
        ->where('correspondence_internal_id', $this->id) // Filtrar por la relación actual
        ->where(function ($query) use ($authUserId) {
            $query->where('users_id', $authUserId);
        })
        ->exists();

    
        return ($exists || $exists1) ? 'Si' : 'No';
    }

    public function getIsOverdueAttribute()
    {
        if ($this->fecha_limite_respuesta) {
            // Convertir fecha_limite_respuesta a un objeto Carbon si no lo es
            $fechaLimite = \Carbon\Carbon::parse($this->fecha_limite_respuesta)->startOfDay();
            $today = now()->startOfDay(); // Obtener la fecha actual sin hora

            // Comparar si la fecha límite es estrictamente anterior a hoy
            return $fechaLimite->lt($today);
        } else {
            return false;
        }
    }

    public function getDataInternaRelacionadaAttribute() {
        if ($this->answer_consecutive_name) {
            // Buscar el registro usando answer_consecutive_name
            $datosInterna = Internal::where('consecutive', $this->answer_consecutive_name)->first();

            // Verificar si se encontró un registro
            if ($datosInterna) {
                $datos = [];

                // Agregar datos al array
                $datos['document_pdf'] = $datosInterna->document_pdf;
                $datos['id_encript'] = base64_encode($datosInterna->id);

                // Retornar los datos
                return $datos;
            } else {
                return 'No se encontró una correspondencia relacionada.';
            }
        } else {
            return 'No tiene una correspondencia relacionada.';
        }
    }


    /**
     * Append para validar si un usuario ya ha leído una correspondencia interna
     *
     * @return void
     */
    public function getLeidoAttribute() {
        // Retorna solo el registro de leido-destacado del usuario en sesión de cada correspondencia
        $leido = $this->internalRead->filter(function($value, $key) {
            return $value["users_id"] == (Auth::user() ? Auth::user()->id : null);
        })->first();
        // Retorna el valor de la propiedad destacado del registro obtenido anteriormente
        return $leido ? $leido->access : null;
    }

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


    public function getQueryAttribute() {
        if (!empty($this->from)) {
            return $this->from;
        }
    }

    public function getObservationInicialAttribute() {
        return $this->observation ?? '';
    }
    /**
     * Append de permiso para ve la accion
     *
     * @return void
     */
    public function getPermissionEditAttribute() {

        switch ($this->state) {
            case 'Elaboración':
                return Auth::user()->id == $this->elaborated_now;
            break;

            case 'Revisión':
                return Auth::user()->id == $this->reviewd_now;
            break;

            case 'Aprobación':
                return Auth::user()->id == $this->approved_now;
            break;

            case 'Pendiente de firma':

                   $lastVersion = $this->internalVersions()->first();

                    if($lastVersion){
                        $sign = $lastVersion->internalSigns->filter(function($value, $key) {
                        return $value["users_id"] == Auth::user()->id && $value["state"] == 'Pendiente de firma';

                    })->first();

                        return $sign ? count($sign->toArray()) > 0 : false;
                    }else{
                        return false;
                    }

            break;

            case 'Devuelto para modificaciones':
                return Auth::user()->id == $this->from_id;
            break;

            default:
                return false;
            break;
        }

    }

    public function getListRecipientsAttribute(){
        return !is_null($this->recipients) ? explode("<br>",$this->recipients) : $this->recipients;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function internalType() {
        return $this->belongsTo(\Modules\Correspondence\Models\InternalTypes::class, 'type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Correspondence\Models\Dependencia::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function internalAnnotations() {
        return $this->hasMany(\Modules\Correspondence\Models\InternalAnnotation::class, 'correspondence_internal_id')->with("users")->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function internalHistory()
    {
        return $this->hasMany(\Modules\Correspondence\Models\InternalHistory::class, 'correspondence_internal_id')->with("users");
    }

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function internalRead()
    {
        return $this->hasMany(\Modules\Correspondence\Models\InternalRead::class, 'correspondence_internal_id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function internalChequeos()
    {
        return $this->hasMany(\Modules\Correspondence\Models\InternalChequeos::class, 'correspondence_internal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function internalCopyShares() {
        return $this->hasMany(\Modules\Correspondence\Models\InternalCopyShare::class, 'correspondence_internal_id');
    }
    public function internalCopy() {
        return $this->internalCopyShares()->where('type','=', 'Copia');
    }

    public function internalShares() {
        return $this->internalCopyShares()->where('type','=', 'Compartida');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function internalRecipients() {
        return $this->hasMany(\Modules\Correspondence\Models\InternalRecipient::class, 'correspondence_internal_id')->with('dependenciaInformacion','users');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function internalVersions() {
        return $this->hasMany(\Modules\Correspondence\Models\InternalVersions::class, 'correspondence_internal_id')->with('internalSigns')->latest();
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
     * Relación con la oficina productora de la clasificación documental
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function historialFirmas() {
        return $this->hasMany(\Modules\Correspondence\Models\InternalSignHistory::class, 'id_documento')
        ->where('tipo', 'Interna');
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
        $userId = Auth::id();

        // Define una relación "hasMany" para recuperar las anotaciones pendientes
        // donde el campo "leido_por" no contiene el ID del usuario actual
        return $this->hasMany(InternalAnnotation::class, 'correspondence_internal_id')
            // ->whereRaw("NOT (leido_por LIKE '%{$userId}%')");
            ->where('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
            ->Where('leido_por', 'not like', $userId . ',%')
            ->where('leido_por', 'not like', '%,' . $userId)
            ->Where('leido_por', 'not like', $userId );
    }

    /**
     * Valida si tiene asociado un expedinete
     * @return
     */
    public function getTieneExpedienteAttribute() {
        if($this->state == "Público") {
            $expediente_info = Expediente::selectRaw("id, consecutivo, id_responsable, nombre_expediente, TO_BASE64(id) AS id_encoded")
                ->whereHas("eeDocumentosExpedientes", function($query) {
                    $query->where("modulo_consecutivo", $this->consecutive);
                })
                ->get()->toArray();
            return $expediente_info ?? null;
        }
    }

}
