<?php

namespace Modules\Configuracion\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class versionsUpdate extends Model
{
    public $table = 'intraweb_version_update';

    public $fillable = [
        'numero_version',
        'descripcion',
        'tipo_de_cambio'
    ];

    protected $casts = [
        'numero_version' => 'string',
        'descripcion' => 'string',
        'tipo_de_cambio' => 'string'
    ];

    public static array $rules = [
        'numero_version' => 'nullable|string|max:120',
        'descripcion' => 'nullable|string',
        'tipo_de_cambio' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
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

}
