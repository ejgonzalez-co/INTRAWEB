<?php

namespace Modules\DocumentosElectronicos\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class DocumentoAnotacion extends Model
{
    public $table = 'de_documento_anotacion';

    public $fillable = [
        'nombre_usuario',
        'contenido',
        'adjuntos',
        'leido_por',
        'de_documento_id',
        'users_id'
    ];

    protected $casts = [
        'users_name' => 'string',
        'content' => 'string',
        'leido_por' => 'string'
    ];

    public static array $rules = [
        'users_name' => 'nullable|string|max:255',
        'content' => 'nullable|string',
        'attached' => 'nullable',
        'leido_por' => 'nullable|string|max:65535',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ["date_format"];

    public function getDateFormatAttribute() {
        $day = $this->created_at->format('d');
        $year = $this->created_at->format('Y');
        $hour = $this->created_at->format('H:i:s');

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

        $month = $months[date('n', strtotime($this->created_at))];

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

        $month_completo = $months[date('n', strtotime($this->created_at))];

        return ['day' => $day, 'year' => $year, 'hour' => $hour, 'month' => $month, 'fecha_completo' => $day.' de '. $month_completo.' de '.$year.' '.$hour];
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

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
