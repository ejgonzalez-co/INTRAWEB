<?php

namespace Modules\ExpedientesElectronicos\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class TiposDocumental extends Model
{
    public $table = 'ee_tipos_documentales';

    public $fillable = [
        'users_id',
        'user_name',
        'tipo_documento',
        'estado'
    ];

    protected $casts = [
        'user_name' => 'string',
        'tipo_documento' => 'string',
        'estado' => 'string'
    ];

    public static array $rules = [
        // 'users_id' => 'required',
        'user_name' => 'nullable|string|max:255',
        'tipo_documento' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:255',
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

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    public function eeDocumentosExpedientes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\DocumentosExpediente::class, 'ee_tipos_documentales_id');
    }
}
