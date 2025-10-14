<?php

namespace Modules\Calidad\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Intranet\Models\Dependency;
use Modules\Intranet\Models\Position;
use Modules\Intranet\Models\User;

class Proceso extends Model
{
    public $table = 'calidad_proceso';

    public $fillable = [
        'nombre',
        'prefijo',
        'estado',
        'orden',
        'id_responsable',
        'tipo_responsable',
        'calidad_tipo_proceso_id',
        'calidad_proceso_id',
        'dependencias_id',
        'calidad_tipo_sistema_id',
        'users_id',
        'usuario_creador'
    ];

    protected $casts = [
        'nombre' => 'string',
        'prefijo' => 'string',
        'estado' => 'string',
        'tipo_responsable' => 'string',
        'usuario_creador' => 'string',
        'dependencias_id' => 'integer',
        'calidad_tipo_proceso_id' => 'integer',
        'calidad_proceso_id' => 'integer'
    ];

    public static array $rules = [
        'nombre' => 'nullable|string|max:255',
        'prefijo' => 'nullable|string|max:45',
        'estado' => 'nullable|string|max:45',
        'orden' => 'nullable',
        'id_responsable' => 'nullable',
        'tipo_responsable' => 'nullable|string|max:70',
        'calidad_tipo_proceso_id' => 'nullable',
        'calidad_proceso_id' => 'nullable',
        'dependencias_id' => 'required',
        'calidad_tipo_sistema_id' => 'required',
        'usuario_creador' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    // Appends del proceso
    protected $appends = ["usuario_responsable", "expanded", "url"];

    public function getUrlAttribute() {
        return config('app.url').'/calidad/documentos-calidad/'.$this->nombre;
    }

    public function getExpandedAttribute() {
        return false;
    }

    /**
     * Devuelve el nombre del usuario o cargo responsable basado en el tipo de responsable.
     * @return string $nombre_responsable
     */
    public function getUsuarioResponsableAttribute() {
        // Divide el valor de 'id_responsable' en un array utilizando "_" como delimitador.
        $responsable = explode("_", $this->id_responsable);
        // Toma el último elemento del array (el verdadero ID del responsable).
        $id_responsable = end($responsable);
        // Si el tipo de responsable es "Cargo", busca en la tabla 'Position' por el ID y obtiene el campo 'nombre'.
        if ($this->tipo_responsable == "Cargo") {
            $responsable = Position::where("id", $id_responsable)->pluck("nombre")->first();
        }
        // Si el tipo de responsable no es "Cargo", se asume que es un usuario, por lo que se busca en la tabla 'User'.
        else {
            $responsable = User::where("id", $id_responsable)->pluck("name")->first();
        }
        // Retorna el nombre del responsable.
        return $responsable;
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

    public function tipoProceso(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\TipoProceso::class, 'calidad_tipo_proceso_id');
    }

    public function proceso(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\Proceso::class, 'calidad_proceso_id');
    }

    public function subprocesosArbol(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Proceso::class, 'calidad_proceso_id')->with("documentosArbol");
    }

    public function tipoSistema(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\TipoSistema::class, 'calidad_tipo_sistema_id');
    }

    public function dependencia(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    public function documentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Documento::class, 'calidad_proceso_id');
    }

    public function documentosArbol(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Documento::class, 'calidad_proceso_id')->where("estado", "Público");
    }

    public function documentoHistorials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\CalidadDocumentoHistorial::class, 'calidad_proceso_id');
    }

    public function documentoSolicitudDocumentals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\CalidadDocumentoSolicitudDocumental::class, 'calidad_proceso_id');
    }

    public function documentoSolicitudDocumental1s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\DocumentoSolicitudDocumental::class, 'calidad_macroproceso_id');
    }

    public function documentoSolicitudDocumentalHistorials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\CalidadDocumentoSolicitudDocumentalHistorial::class, 'calidad_proceso_id');
    }

    public function documentoSolicitudDocumentalHistorial2s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\CalidadDocumentoSolicitudDocumentalHistorial::class, 'calidad_macroproceso_id');
    }

    public function setIdResponsableAttribute($value)
    {
        $value = explode("_", $value);
        $this->attributes['id_responsable'] = end($value);
    }

    public function getIdResponsableAttribute($value)
    {
        return $this->tipo_responsable."_".$value;
    }

    public function subprocesosMapa(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Proceso::class, 'calidad_proceso_id')->whereNotNull("calidad_proceso_id");
    }
}
