<?php

namespace Modules\Correspondence\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class PlanillaRuta extends Model
{
    public $table = 'correspondence_planilla_ruta';

    public $fillable = [
        'nombre_ruta',
        'descripcion',
        'users_id',
        'nombre_usuario'
    ];

    protected $casts = [
        'nombre_ruta' => 'string',
        'descripcion' => 'string',
        'nombre_usuario' => 'string'
    ];

    public static array $rules = [
        'nombre_ruta' => 'required|string|max:100',
        'descripcion' => 'nullable|string|max:150',
        'nombre_usuario' => 'nullable|string|max:90',
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

    public function planillaRutaDependencias(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Correspondence\Models\PlanillaRutaDependencia::class, 'correspondence_planilla_ruta_id')->with("dependencias");
    }

    public function dependencias(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\Modules\Correspondence\Models\Dependencia::class, 'correspondence_planilla_ruta_has_dependencias');
    }
}
