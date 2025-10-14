<?php

namespace Modules\Calidad\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class TipoProceso extends Model
{
    public $table = 'calidad_tipo_proceso';

    public $fillable = [
        'nombre',
        'prefijo',
        'estado',
        'orden',
        'users_id',
        'usuario_creador'
    ];

    protected $casts = [
        'nombre' => 'string',
        'prefijo' => 'string',
        'estado' => 'string',
        'usuario_creador' => 'string'
    ];

    public static array $rules = [
        'nombre' => 'nullable|string|max:255',
        'prefijo' => 'nullable|string|max:45',
        'estado' => 'nullable|string|max:45',
        'orden' => 'nullable',
        'usuario_creador' => 'nullable|string|max:100',
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
        return $this->belongsTo(\Modules\Calidad\Models\User::class, 'users_id');
    }

    public function procesos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Proceso::class, 'calidad_tipo_proceso_id');
    }

    public function procesosMapa(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Proceso::class, 'calidad_tipo_proceso_id')->whereNull("calidad_proceso_id")->with("subprocesosMapa");
    }
}
