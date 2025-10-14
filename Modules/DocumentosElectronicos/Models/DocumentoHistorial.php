<?php

namespace Modules\DocumentosElectronicos\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class DocumentoHistorial extends Model
{
    public $table = 'de_documento_historial';

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
        'observacion_historial',
        'codigo_acceso_documento',
        'vigencia',
        'users_id',
        'users_name',
        'de_tipos_documentos_id',
        'de_documento_id',
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
        'observacion_historial' => 'string',
        'codigo_acceso_documento' => 'string',
        'users_name' => 'string'
    ];

    public static array $rules = [
        'consecutivo' => 'required|string|max:200',
        'consecutivo_prefijo' => 'nullable|string|max:200',
        'titulo_asunto' => 'required|string|max:250',
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
        'observacion_historial' => 'nullable|string|max:255',
        'codigo_acceso_documento' => 'nullable|string|max:100',
        'vigencia' => 'required',
        'users_id' => 'required',
        'users_name' => 'nullable|string|max:200',
        'de_tipos_documentos_id' => 'nullable',
        'de_documento_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = [
        'date_format_day',
        'date_format_month',
        'date_format_year',
        'date_format_month_completo',
        'date_format_hour'

    ];

        /**
     * Append
     *
     * @return void
     */
    public function getDateFormatDayAttribute() {

        return $this->created_at->format('d');

    }

    public function getDateFormatYearAttribute() {

        return $this->created_at->format('Y');

    }

    public function getDateFormatHourAttribute() {

        return $this->created_at->format('H:i:s');

    }

    public function getDateFormatMonthAttribute() {
        $months = [
            1 => 'ENE',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'ABR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGO',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DIC',
        ];

        return $months[date('n', strtotime($this->created_at))];
    }

    public function getDateFormatMonthCompletoAttribute() {
        $months = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];

        return $months[date('n', strtotime($this->created_at))];
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

    public function deDocumento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\Documento::class, 'de_documento_id');
    }

    public function deTiposDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\TipoDocumento::class, 'de_tipos_documentos_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
