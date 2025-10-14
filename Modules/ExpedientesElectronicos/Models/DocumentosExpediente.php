<?php

namespace Modules\ExpedientesElectronicos\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Auth;

class DocumentosExpediente extends Model
{
    public $table = 'ee_documentos_expediente';

    public $fillable = [
        'ee_expediente_id',
        'users_id',
        'ee_tipos_documentales_id',
        'user_name',
        'nombre_expediente',
        'nombre_tipo_documental',
        'origen_creacion',
        'nombre_documento',
        'fecha_documento',
        'descripcion',
        'pagina_inicio',
        'pagina_fin',
        'adjunto',
        'modulo_intraweb',
        'consecutivo',
        'vigencia',
        'modulo_consecutivo',
        'orden_documento',
        'estado_doc',
        'hash_value',
        'hash_algoritmo'
    ];

    protected $casts = [
        'user_name' => 'string',
        'nombre_expediente' => 'string',
        'nombre_tipo_documental' => 'string',
        'origen_creacion' => 'string',
        'nombre_documento' => 'string',
        'fecha_documento' => 'datetime',
        'descripcion' => 'string',
        'modulo_intraweb' => 'string',
        'consecutivo' => 'string'
    ];

    public static array $rules = [

        'user_name' => 'nullable|string|max:255',
        'nombre_expediente' => 'nullable|string|max:255',
        'nombre_tipo_documental' => 'nullable|string|max:255',
        'origen_creacion' => 'nullable|string|max:255',
        'nombre_documento' => 'nullable|string|max:255',
        'fecha_documento' => 'nullable',
        'descripcion' => 'nullable|string',
        'pagina_inicio' => 'nullable',
        'pagina_fin' => 'nullable',
        'modulo_intraweb' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ['info_documento'];

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

    public function eeExpediente(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\ExpedientesElectronicos\Models\Expediente::class, 'ee_expediente_id');
    }

    public function eeTiposDocumentales(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // return $this->belongsTo(\Modules\ExpedientesElectronicos\Models\TiposDocumental::class, 'ee_tipos_documentales_id');
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\typeDocumentaries::class, 'ee_tipos_documentales_id')->withTrashed()->orderByDesc('deleted_at');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function eeDocExpedienteHistorials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\EeDocExpedienteHistorial::class, 'ee_documentos_expediente_id');
    }

    public function getInfoDocumentoAttribute() {
        if($this->modulo_intraweb == "Correspondencia interna"){
            $informacion_documento = \Modules\Correspondence\Models\Internal::with(['internalType','internalCopy','internalHistory', 'internalAnnotations', 'internalRead', 'internalRecipients', 'internalVersions', 'internalShares', 'serieClasificacionDocumental', 'internalCopyShares', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'historialFirmas', 'users'])->where('consecutive', $this->modulo_consecutivo)->get()->makeHidden(['permission_check', 'status_permission_check']);
            return $informacion_documento;
        } else if($this->modulo_intraweb == "PQRSD"){
            $informacion_documento = \Modules\PQRS\Models\PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental'])->where('pqr_id', $this->modulo_consecutivo)->get();
            return $informacion_documento;
        } else if($this->modulo_intraweb == "Correspondencia recibida"){
            $informacion_documento = \Modules\Correspondence\Models\ExternalReceived::with(['typeDocumentary','dependencies','functionaries','citizens', 'externalAnnotations', 'externalRead', 'externalCopy','externalHistory', 'externalShares', 'externalCopyShares', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental'])->where('consecutive', $this->modulo_consecutivo)->get();
            return $informacion_documento;
        } else if($this->modulo_intraweb == "Correspondencia enviada"){
            $informacion_documento = \Modules\Correspondence\Models\External::with(['externalReceiveds', 'externalType', 'externalCopy', 'externalHistory', 'externalAnnotations', 'externalRead', 'externalShares', 'externalVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'citizens'])->where('consecutive', $this->modulo_consecutivo)->get();
            return $informacion_documento;
        } else if($this->modulo_intraweb == "Documentos electrónicos"){
            $informacion_documento = \Modules\DocumentosElectronicos\Models\Documento::with(['deTiposDocumentos',
                    'deDocumentoHistorials',
                    'documentoAnotacions',
                    'deCompartidos',
                    'deDocumentoVersions',
                    'serieClasificacionDocumental',
                    'subserieClasificacionDocumental',
                    'oficinaProductoraClasificacionDocumental',
                    'users',
                    'deDocumentoFirmars',
                    'documentoLeido',
                    'documentoAsociado',
                    'deDocumentoHasDeMetadatos'])->where('consecutivo', $this->modulo_consecutivo)->get();
            return $informacion_documento;
        } else {
            return null;
        }
    }

    public function documentoHasMetadatos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\ExpedienteHasMetadato::class, 'ee_documentos_expediente_id')->with("metadatos");
    }

    public function documentoExpedienteAnotaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\DocumentoExpedienteAnotacion::class, 'ee_documentos_expediente_id')->with(["users"])->latest();
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

        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\DocumentoExpedienteAnotacion::class, 'ee_documentos_expediente_id')
            ->where('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
            ->Where('leido_por', 'not like', $userId . ',%')
            ->where('leido_por', 'not like', '%,' . $userId)
            ->Where('leido_por', 'not like', $userId );
    }
}
