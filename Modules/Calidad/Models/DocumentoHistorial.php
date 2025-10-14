<?php

namespace Modules\Calidad\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class DocumentoHistorial extends Model
{
    public $table = 'calidad_documento_historial';

    public $fillable = [
        'titulo',
        'version',
        'estado',
        'orden',
        'consecutivo',
        'consecutivo_prefijo',
        'separador_consecutivo',
        'plantilla',
        'documento_adjunto',
        'document_pdf',
        'clase',
        'visibilidad_documento',
        'classification_production_office',
        'classification_serie',
        'classification_subserie',
        'formato_publicacion',
        'hash_document_pdf',
        'validation_code',
        'elaboro_id',
        'reviso_id',
        'aprobo_id',
        'publico_id',
        'elaboro_nombres',
        'reviso_nombres',
        'aprobo_nombres',
        'publico_nombres',
        'elaboro_cargos',
        'reviso_cargos',
        'aprobo_cargos',
        'publico_cargos',
        'fechas_elaboro',
        'fechas_reviso',
        'fechas_aprobo',
        'fecha_publico',
        'distribucion',
        'users_id_actual',
        'users_name_actual',
        'tipo_documento',
        'proceso',
        'codigo_formato',
        'observacion',
        'origen_documento',
        'formato_archivo',
        'observacion_historial',
        'vigencia',
        'users_id',
        'users_name',
        'calidad_documento_tipo_documento_id',
        'calidad_proceso_id',
        'calidad_documento_solicitud_documental_id',
        'calidad_tipo_sistema_id',
        'calidad_documento_id'
    ];

    protected $casts = [
        'titulo' => 'string',
        'version' => 'string',
        'estado' => 'string',
        'consecutivo' => 'string',
        'plantilla' => 'string',
        'documento_adjunto' => 'string',
        'document_pdf' => 'string',
        'clase' => 'string',
        'visibilidad_documento' => 'string',
        'formato_publicacion' => 'string',
        'hash_document_pdf' => 'string',
        'validation_code' => 'string',
        'elaboro_id' => 'string',
        'reviso_id' => 'string',
        'aprobo_id' => 'string',
        'publico_id' => 'string',
        'elaboro_nombres' => 'string',
        'reviso_nombres' => 'string',
        'aprobo_nombres' => 'string',
        'publico_nombres' => 'string',
        'elaboro_cargos' => 'string',
        'reviso_cargos' => 'string',
        'aprobo_cargos' => 'string',
        'publico_cargos' => 'string',
        'distribucion' => 'string',
        'users_name_actual' => 'string',
        'tipo_documento' => 'string',
        'proceso' => 'string',
        'observacion' => 'string',
        'origen_documento' => 'string',
        'formato_archivo' => 'string',
        'users_name' => 'string'
    ];

    public static array $rules = [
        'titulo' => 'required|string|max:250',
        'version' => 'nullable|string|max:45',
        'estado' => 'nullable|string|max:45',
        'orden' => 'nullable',
        'consecutivo' => 'required|string|max:200',
        'consecutivo_prefijo' => 'nullable',
        'plantilla' => 'nullable|string',
        'documento_adjunto' => 'nullable|string',
        'document_pdf' => 'nullable|string',
        'clase' => 'nullable|string|max:100',
        'visibilidad_documento' => 'nullable|string|max:45',
        'classification_production_office' => 'nullable',
        'classification_serie' => 'nullable',
        'classification_subserie' => 'nullable',
        'formato_publicacion' => 'nullable|string|max:100',
        'hash_document_pdf' => 'nullable|string|max:100',
        'validation_code' => 'nullable|string|max:15',
        'elaboro_id' => 'nullable|string|max:255',
        'reviso_id' => 'nullable|string|max:255',
        'aprobo_id' => 'nullable|string|max:255',
        'publico_id' => 'nullable|string|max:255',
        'elaboro_nombres' => 'nullable|string|max:65535',
        'reviso_nombres' => 'nullable|string|max:65535',
        'aprobo_nombres' => 'nullable|string|max:65535',
        'publico_nombres' => 'nullable|string|max:65535',
        'elaboro_cargos' => 'nullable|string|max:65535',
        'reviso_cargos' => 'nullable|string|max:65535',
        'aprobo_cargos' => 'nullable|string|max:65535',
        'publico_cargos' => 'nullable|string|max:65535',
        'distribucion' => 'nullable|string',
        'users_id_actual' => 'nullable',
        'users_name_actual' => 'nullable|string|max:200',
        'tipo_documento' => 'nullable|string|max:100',
        'proceso' => 'nullable|string|max:200',
        'observacion' => 'nullable|string|max:65535',
        'origen_documento' => 'nullable|string|max:100',
        'formato_archivo' => 'nullable|string|max:90',
        'vigencia' => 'required',
        'users_id' => 'required',
        'users_name' => 'nullable|string|max:200',
        'calidad_documento_tipo_documento_id' => 'required',
        'calidad_proceso_id' => 'required',
        'calidad_documento_solicitud_documental_id' => 'nullable',
        'calidad_tipo_sistema_id' => 'nullable',
        'calidad_documento_id' => 'required',
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

    public function documentoSolicitudDocumental(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\DocumentoSolicitudDocumental::class, 'calidad_documento_solicitud_documental_id');
    }

    public function documentoTipoDocumento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\DocumentoTipoDocumento::class, 'calidad_documento_tipo_documento_id');
    }

    public function proceso(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\Proceso::class, 'calidad_proceso_id');
    }

    public function tipoSistema(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\TipoSistema::class, 'calidad_tipo_sistema_id');
    }

    public function documento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\Documento::class, 'calidad_documento_id');
    }
}
