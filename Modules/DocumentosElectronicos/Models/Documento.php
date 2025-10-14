<?php

namespace Modules\DocumentosElectronicos\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use stdClass;
use Modules\ExpedientesElectronicos\Models\Expediente;

class Documento extends Model
{
    public $table = 'de_documento';

    public $fillable = [
        'consecutivo',
        'consecutivo_prefijo',
        'titulo_asunto',
        'plantilla',
        'documento_adjunto',
        'compartidos',
        'document_pdf',
        'estado',
        'subestado_documento',
        'adjuntos',
        'classification_production_office',
        'classification_serie',
        'classification_subserie',
        'documentos_asociados',
        'formato_publicacion',
        'hash_document_pdf',
        'validation_code',
        'elaboro_id',
        'reviso_id',
        'tipo_usuario',
        'correo_usuario',
        'elaboro_nombres',
        'reviso_nombres',
        'users_id_actual',
        'users_name_actual',
        'observacion',
        'codigo_acceso_documento',
        'origen_documento',
        'vigencia',
        'users_id',
        'users_name',
        'funcionario_elaboracion_revision_object',
        'de_tipos_documentos_id',
        'dependencia_id',
        'dependencia_nombre',
        'require_answer',
        'tipo_finalizacion',
        'pqr_id',
        'pqr_consecutive',
    ];

    protected $casts = [
        'consecutivo' => 'string',
        'consecutivo_prefijo' => 'string',
        'titulo_asunto' => 'string',
        'plantilla' => 'string',
        'documento_adjunto' => 'string',
        'compartidos' => 'string',
        'estado' => 'string',
        'subestado_documento' => 'string',
        'documentos_asociados' => 'string',
        'formato_publicacion' => 'string',
        'hash_document_pdf' => 'string',
        'validation_code' => 'string',
        'elaboro_id' => 'string',
        'reviso_id' => 'string',
        'tipo_usuario' => 'string',
        'correo_usuario' => 'string',
        'elaboro_nombres' => 'string',
        'reviso_nombres' => 'string',
        'observacion' => 'string',
        'codigo_acceso_documento' => 'string',
        'origen_documento' => 'string',
        'users_name' => 'string',
        'funcionario_elaboracion_revision_object' => 'string'
    ];

    public static array $rules = [
        'consecutivo_prefijo' => 'nullable|string|max:200',
        'titulo_asunto' => 'required|string|max:400',
        'plantilla' => 'nullable|string',
        'documento_adjunto' => 'nullable|string',
        'compartidos' => 'nullable|string',
        'estado' => 'nullable|string|max:45',
        'subestado_documento' => 'nullable|string|max:200',
        'classification_production_office' => 'nullable',
        'classification_serie' => 'nullable',
        'classification_subserie' => 'nullable',
        'documentos_asociados' => 'nullable|string|max:45',
        'formato_publicacion' => 'nullable|string|max:100',
        'hash_document_pdf' => 'nullable|string|max:100',
        'validation_code' => 'nullable|string|max:15',
        'elaboro_id' => 'nullable|string|max:255',
        'reviso_id' => 'nullable|string|max:255',
        'tipo_usuario' => 'nullable|string|max:100',
        'correo_usuario' => 'nullable|string|max:255',
        'elaboro_nombres' => 'nullable|string|max:65535',
        'reviso_nombres' => 'nullable|string|max:65535',
        'users_id_actual' => 'nullable',
        'users_name_actual' => 'nullable',
        'observacion' => 'nullable|string|max:65535',
        'codigo_acceso_documento' => 'nullable|string|max:100',
        'origen_documento' => 'nullable|string|max:100',
        'vigencia' => 'required',
        'users_id' => 'required',
        'users_name' => 'nullable|string|max:200',
        'de_tipos_documentos_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ['id_encode', 'metadatos', 'permission_edit','see_conjunt_signatures','leido','is_eliminable','is_editable','tiene_expediente'];

    /**
     * Append para validar si un usuario ya ha leído un documento electrónico
     *
     * @return void
     */
    public function getLeidoAttribute() {
        // Retorna solo el registro de leido-destacado del usuario en sesión de cada Documento
        $leido = $this->documentoLeido->filter(function($value, $key) {
            return $value["users_id"] == (Auth::user() ? Auth::user()->id : null);
        })->first();
        // Retorna el valor de la propiedad destacado del registro obtenido anteriormente
        return $leido ? $leido->accesos : null;
    }

    /**
     * Append de permiso para ve la accion
     *
     * @return void
     */
    public function getPermissionEditAttribute() {

        switch ($this->estado) {
            case 'Elaboración':
            case 'Revisión':
                return Auth::user()->id == $this->users_id_actual;
            break;

            case 'Pendiente de firma':
                $lastVersion = $this->deDocumentoVersions()->first();

                if($lastVersion){
                    $sign = $lastVersion->deDocumentoFirmars->filter(function($value, $key) {
                        return Auth::user() && $value["users_id"] == Auth::user()->id && $value["estado"] == 'Pendiente de firma';
                    })->first();
                    return $sign ? count($sign->toArray()) > 0 : false;
                }else{
                    return false;
                }
            break;

            case 'Devuelto para modificaciones':
                    return Auth::check() ? Auth::user()->id == $this->users_id : '';
            break;

            case 'Revisión (Editado por externo)':
                    return Auth::check() ? Auth::user()->id == $this->users_id : '';
            break;
            
            case 'Pendiente de firma (pendiente de enviar)':
            case 'publicación (pendiente de enviar)':
            case 'Elaboración (pendiente de enviar)':
            case 'Revisión (pendiente de enviar)':
                return Auth::check() ? Auth::user()->id == $this->users_id : '';
                break;


            default:
                return false;
            break;
        }

    }

    public function getIdEncodeAttribute() {
        return base64_encode($this->id);
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

    public function deTiposDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\TipoDocumento::class, 'de_tipos_documentos_id')->with("deMetadatos");
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    public function deCompartidos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoCompartir::class, 'de_documentos_id');
    }

    public function deDocumentoFirmars(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoFirmar::class, 'de_documentos_id')->with("usuarios");
    }

    public function deDocumentoHasDeMetadatos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoHasMetadato::class, 'de_documentos_id')->with("deMetadatos");
    }

    public function getMetadatosAttribute() {
        $metadatos = [];
        $metadatos_value = $this->deDocumentoHasDeMetadatos()->get()->toArray();
        for ($i = 0; $i < count($metadatos_value); $i++) {
            // Agregar el valor al array
            $metadatos["metadato_{$metadatos_value[$i]['de_metadatos_id']}"] = $metadatos_value[$i]["valor"];
        }

        return (object) $metadatos;
    }

    public function deDocumentoHistorials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoHistorial::class, 'de_documento_id')->with(["deTiposDocumentos", "users"]);
    }

    public function deDocumentoVersions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoVersion::class, 'de_documentos_id')->with('deDocumentoFirmars')->latest();
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documentoAnotacions() {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoAnotacion::class, 'de_documento_id')->with("users");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documentoLeido() {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoLeido::class, 'de_documento_id');
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
        return $this->hasMany(DocumentoAnotacion::class, 'de_documento_id')
            ->where('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
            ->Where('leido_por', 'not like', $userId . ',%')
            ->where('leido_por', 'not like', '%,' . $userId)
            ->Where('leido_por', 'not like', $userId )
            ->orWhereNull('leido_por');

    }

    /**
     * Devuelve un booleano si al menos un usuario ya firmo el documento cuanto es firma conjunta.
     *
     */
    public function getSeeConjuntSignaturesAttribute() : bool{
        $quantitySignatures = DocumentoFirmar::where("de_documentos_id",$this->id)->whereNotNull("hash")->count();
        return $quantitySignatures > 0;
    }

    /**
     * Devuelve un booleano si el tipo de documento se puede eliminar.
     *
     */
    public function getIsEliminableAttribute() : bool{
        if($this->origen_documento == "Adjuntar documento para ser almacenado en Intraweb" && $this->users_id == Auth::user()->id){
            $isEliminable = TipoDocumento::select("es_borrable")->where("id",$this->de_tipos_documentos_id)->first();
            if(!empty($isEliminable)){
                return $isEliminable["es_borrable"] == true;
            }
            return false;
        }
        return false;
    }

    /**
     * Devuelve un booleano si el tipo de documento se puede editar.
     *
     */
    public function getIsEditableAttribute() : bool{
        if($this->origen_documento == "Adjuntar documento para ser almacenado en Intraweb" && $this->users_id == Auth::user()->id){
            $isEditable = TipoDocumento::select("es_editable")->where("id",$this->de_tipos_documentos_id)->first();
            if(!empty($isEditable)){
                return $isEditable["es_editable"] == true;
            }
            return false;
        }
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function documentoAsociado() {
        return $this->hasOne(\Modules\DocumentosElectronicos\Models\Documento::class, 'id', 'documentos_asociados');
    }

    /**
     * Valida si tiene asociado un expedinete
     * @return
     */
    public function getTieneExpedienteAttribute() {
        if($this->estado == "Público") {
            $expediente_info = Expediente::selectRaw("id, consecutivo, id_responsable, nombre_expediente, TO_BASE64(id) AS id_encoded")
                ->whereHas("eeDocumentosExpedientes", function($query) {
                    $query->where("modulo_consecutivo", $this->consecutivo);
                })
                ->get()->toArray();
            return $expediente_info ?? null;
        }
    }
}
