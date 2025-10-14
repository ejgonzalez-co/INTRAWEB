<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class CorreoIntegradoSpam extends Model
{
    public $table = 'comunicaciones_por_correo_spam';

    public $fillable = [
        'correo_remitente',
        'uid',
        'fecha',
        'asunto'
    ];

    protected $casts = [
        'correo_remitente' => 'string',
        'uid' => 'string',
        'fecha' => 'datetime',
        'asunto' => 'string'
    ];

    public static array $rules = [
        'correo_remitente' => 'required|string|max:255',
        'uid' => 'required|string',
        'fecha' => 'required',
        'asunto' => 'required',
        'created_at' => 'required',
        'updated_at' => 'required',
        'deleted_at' => 'required'
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
