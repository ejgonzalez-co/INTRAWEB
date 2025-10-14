<?php

namespace Modules\PQRS\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class PQRHistorial extends Model
{
    public $table = 'pqr_historial';

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
        'vigencia',
        'users_id',
        'funcionario_users_id',
        'ciudadano_users_id',
        'pqr_eje_tematico_id',
        'pqr_tipo_solicitud_id',
        'pqr_pqr_id',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'linea_tiempo',
        'users_name',
        'action',
        'validation_code',
        'no_matricula',
        'direccion_predio',
        'motivos_hechos',
        'respuesta_correo'
    ];

    protected $casts = [
        'pqr_id' => 'string',
        'nombre_ciudadano' => 'string',
        'documento_ciudadano' => 'string',
        'email_ciudadano' => 'string',
        'document_pdf' => 'string',
        'adjunto_ciudadano' => 'string',
        'contenido' => 'string',
        'anexos' => 'string',
        'canal' => 'string',
        'respuesta' => 'string',
        'adjunto' => 'string',
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
        'fecha_vence' => 'datetime',
        'fecha_fin' => 'datetime',
        'fecha_temprana' => 'datetime',
        'funcionario_destinatario' => 'string',
        'pregunta_ciudadano' => 'string',
        'respuesta_ciudadano' => 'string',
        'respuesta_correo' => 'integer'
    ];

    public static array $rules = [
        'pqr_id' => 'required|string|max:45',
        'nombre_ciudadano' => 'nullable|string|max:255',
        'documento_ciudadano' => 'nullable|string|max:255',
        'email_ciudadano' => 'nullable|string|max:255',
        'document_pdf' => 'nullable|string',
        'adjunto_ciudadano' => 'nullable|string',
        'contenido' => 'nullable|string',
        'folios' => 'nullable',
        'anexos' => 'nullable|string|max:255',
        'canal' => 'nullable|string|max:150',
        'respuesta' => 'nullable|string',
        'adjunto' => 'nullable|string',
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
        'adj_oficio_respuesta' => 'nullable|string',
        'no_oficio_solicitud' => 'nullable|string|max:100',
        'adj_oficio_solicitud' => 'nullable|string',
        'tipo_finalizacion' => 'nullable|string|max:255',
        'tipo_adjunto' => 'nullable|string|max:255',
        'correspondence_external_received_id' => 'nullable',
        'fecha_fin_parcial' => 'nullable',
        'respuesta_parcial' => 'nullable|string',
        'fecha_vence' => 'nullable',
        'fecha_fin' => 'nullable',
        'fecha_temprana' => 'nullable',
        'funcionario_destinatario' => 'nullable|string|max:100',
        'pregunta_ciudadano' => 'nullable|string',
        'respuesta_ciudadano' => 'nullable|string',
        'empresa_traslado' => 'nullable|string|max:250',
        'vigencia' => 'nullable',
        'users_id' => 'required',
        'funcionario_users_id' => 'nullable',
        'ciudadano_users_id' => 'nullable',
        'pqr_eje_tematico_id' => 'nullable',
        'pqr_tipo_solicitud_id' => 'required',
        'pqr_pqr_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = [
        'date_format'
    ];

    /**
     * Append
     *
     * @return void
     */
    public function getDateFormatAttribute() {

        $dateFormat['day'] = $this->created_at->format('d');
        $dateFormat['year'] = $this->created_at->format('Y');
        $dateFormat['hour'] = $this->created_at->format('H:i:s');
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

        $dateFormat['month'] = $months[date('n', strtotime($this->created_at))];

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
        $dateFormat['monthcompleto'] = $months[date('n', strtotime($this->created_at))];

        return $dateFormat;

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

    public function pqrPqr(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQR::class, 'pqr_pqr_id');
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
        return $this->belongsTo(\App\User::class, 'funcionario_users_id');
    }

    public function ciudadanoUsers(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'ciudadano_users_id');
    }
}
