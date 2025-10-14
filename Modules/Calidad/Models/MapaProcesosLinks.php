<?php

namespace Modules\Calidad\Models;

use Illuminate\Database\Eloquent\Model;

class MapaProcesosLinks extends Model
{
    public $table = 'calidad_mapa_procesos_links';

    public $fillable = [
        'desplazamiento_x',
        'desplazamiento_y',
        'porcentaje_x',
        'porcentaje_y',
        'porcentaje_w',
        'porcentaje_h',
        'ancho',
        'alto',
        'link_id',
        'url',
        'nombre_usuario',
        'users_id',
        'calidad_mapa_procesos_id'
    ];

    protected $casts = [
        'link_id' => 'string',
        'nombre_usuario' => 'string'
    ];

    public static array $rules = [
        'desplazamiento_x' => 'nullable',
        'desplazamiento_y' => 'nullable',
        'ancho' => 'nullable',
        'alto' => 'nullable',
        'link_id' => 'nullable|string|max:150',
        'nombre_usuario' => 'nullable|string|max:45',
        'users_id' => 'required',
        'calidad_mapa_procesos_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ["editing"];
    // Append para indicar en el front si el link se está editando o no
    public function getEditingAttribute() {
        return false;
    }

    public function calidadMapaProcesos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\CalidadMapaProceso::class, 'calidad_mapa_procesos_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\User::class, 'users_id');
    }
}
