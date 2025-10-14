<?php

namespace Modules\PQRS\Repositories;

use Modules\PQRS\Models\PQR;
use App\Repositories\BaseRepository;

class PQRRepository extends BaseRepository
{
    protected $fieldSearchable = [
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
        'fecha_vence',
        'fecha_fin',
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
        'no_matricula',
        'direccion_predio',
        'motivos_hechos',
        'respuesta_correo'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PQR::class;
    }
}
