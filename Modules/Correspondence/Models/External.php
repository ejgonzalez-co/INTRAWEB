<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;
use Illuminate\Support\Facades\Auth;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;
use Modules\ExpedientesElectronicos\Models\Expediente;

/**
 * Class External
 * @package Modules\Correspondence\Models
 * @version January 19, 2022, 3:53 pm -05
 *
 * @property Modules\Correspondence\Models\CorrespondenceExternalType $type
 * @property Modules\Correspondence\Models\Dependencia $dependencias
 * @property Modules\Intranet\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceExternalAnnotations
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceExternalCopyShares
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceExternalRecipients
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
class External extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_external';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'consecutive',
        'external_received_id',
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
        'template_preview',
        'editor',
        'origen',
        'citizen_name',
        'citizen_document',
        'citizen_id',
        'citizen_email',
        'city_id',
        'department_id',
        'department_name',
        'city_name',
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
        'external_all',
        'copies',
        'guia',
        'channel',
        'tipo_finalizacion',
        'pqr_id',
        'pqr_consecutive',
        'annexes_digital',
        'hash_document_pdf',
        'validation_code',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'have_assigned_correspondence_received',
        'external_received_consecutive'
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
        'editor' => 'string',
        'origen' => 'string',
        'citizen_name' => 'string',
        'citizen_id' => 'string',
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
        'city_id'=> 'integer',
        'department_id'=> 'integer',
        'classification_serie'=> 'integer',
        'classification_subserie'=> 'integer',
        'classification_production_office'=> 'integer',
        'external_received_external'=>'string'

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
        'require_answer' => 'nullable|string|max:45',
        'answer_consecutive' => 'nullable|string|max:255',
        'template' => 'nullable|string|max:255',
        'editor' => 'nullable|string|max:255',
        'origen' => 'nullable|string|max:45',
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
        'user_from_last_update' => 'nullable|string|max:255',
        'user_for_last_update' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


    protected $appends = [
        'query',
        'permission_edit',
        'observation_inicial',
        'count_rebounds',
        'leido',
        'tiene_expediente',
        'channel_name'
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
     * Append para validar si un usuario ya ha leído una correspondencia enviada
     *
     * @return void
     */
    public function getLeidoAttribute() {
        // Retorna solo el registro de leido-destacado del usuario en sesión de cada correspondencia
        $leido = $this->externalRead->filter(function($value, $key) {
            return $value["users_id"] == (Auth::user() ? Auth::user()->id : null);
        })->first();
        // Retorna el valor de la propiedad destacado del registro obtenido anteriormente
        return $leido ? $leido->access : null;
    }


    public function getQueryAttribute() {
        if (!empty($this->from)) {
            return $this->from;
        }
    }

    public function getChannelNameAttribute() {
        if ($this->channel == 1) {
            return 'Correo certificado';
        }elseif ($this->channel == 2) {
            return 'Correo electrónico';

        }elseif ($this->channel == 3) {
            return 'Fax';

        }elseif ($this->channel == 4) {
            return 'Personal';

        }elseif ($this->channel == 5) {
            return 'Telefónico';

        }elseif ($this->channel == 6) {
            return 'Web';

        }elseif ($this->channel == 7) {
            return 'Notificación por aviso';

        }else {
            return 'Buzón de sugerencias';

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

                   $lastVersion = $this->externalVersions()->first();

                    if($lastVersion){
                        $sign = $lastVersion->externalSigns->filter(function($value, $key) {
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function externalType() {
        return $this->belongsTo(\Modules\Correspondence\Models\ExternalTypes::class, 'type');
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
    public function externalReceiveds() {
        return $this->belongsTo(\Modules\Correspondence\Models\ExternalReceived::class, 'external_received_id');
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
    public function externalAnnotations() {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalAnnotation::class, 'correspondence_external_id')->with("users")->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalHistory()
    {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalHistory::class, 'correspondence_external_id')->with("users");
    }


     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalRead()
    {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalRead::class, 'correspondence_external_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalCopyShares() {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalCopyShare::class, 'correspondence_external_id');
    }
    public function externalCopy() {
        return $this->externalCopyShares()->where('type','=', 'Copia');
    }

    public function externalShares() {
        return $this->externalCopyShares()->where('type','=', 'Compartida');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalRecipients() {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalRecipient::class, 'correspondence_external_id');
    }

    public function externalVersions() {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalVersions::class, 'correspondence_external_id')->with('externalSigns')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function citizens() {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalCitizen::class, 'correspondence_external_id');
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
        $userId = Auth::id();

        // Define una relación "hasMany" para recuperar las anotaciones pendientes
        // donde el campo "leido_por" no contiene el ID del usuario actual
        return $this->hasMany(ExternalAnnotation::class, 'correspondence_external_id')
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
