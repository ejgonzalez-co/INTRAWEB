<?php

namespace Modules\Calidad\Models;

use Illuminate\Database\Eloquent\Model;

class MapaProcesos extends Model
{
    public $table = 'calidad_mapa_procesos';

    public $fillable = [
        'nombre_usuario',
        'adjunto',
        'descripcion',
        'vigencia',
        'users_id'
    ];

    protected $casts = [
        'nombre_usuario' => 'string',
        'adjunto' => 'string'
    ];

    public static array $rules = [
        'nombre_usuario' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function mapaProcesosLinks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\MapaProcesosLinks::class, 'calidad_mapa_procesos_id');
    }
}
