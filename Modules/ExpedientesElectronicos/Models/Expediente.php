<?php

namespace Modules\ExpedientesElectronicos\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Auth;

class Expediente extends Model
{
    public $table = 'ee_expediente';

    public $fillable = [
        'users_id',
        'dependencias_id',
        'nombre_expediente',
        'consecutivo',
        'user_name',
        'nombre_dependencia',
        'fecha_inicio_expediente',
        'descripcion',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'existe_fisicamente',
        'ubicacion',
        'sede',
        'area_archivo',
        'estante',
        'modulo',
        'entrepano',
        'caja',
        'cuerpo',
        'unidad_conservacion',
        'fecha_archivo',
        'numero_inventario',
        'estado',
        'observacion',
        'vigencia',
        'consecutivo_order',
        'id_firma_caratula_apertura',
        'id_firma_caratula_cierre',
        'id_responsable',
        'nombre_responsable',
        'id_expediente',
        'ip_apertura',
        'ip_cierre',
        'justificacion_cierre',
        'permiso_general_expediente',
        'observacion_accion',
        'id_usuario_enviador',
        'nombre_usuario_enviador',
        'tipo_usuario_enviador',
        'hash_caratula_apertura',
        'hash_caratula_cierre',
        'codigo_acceso_caratula_apertura',
        'codigo_acceso_caratula_cierre'
    ];

    protected $casts = [
        'nombre_expediente' => 'string',
        'user_name' => 'string',
        'nombre_dependencia' => 'string',
        // 'fecha_inicio_expediente' => 'datetime',
        'descripcion' => 'string',
        'existe_fisicamente' => 'string',
        'ubicacion' => 'string',
        'sede' => 'string',
        'area_archivo' => 'string',
        'estante' => 'string',
        'modulo' => 'string',
        'entrepano' => 'string',
        'caja' => 'string',
        'cuerpo' => 'string',
        'unidad_conservacion' => 'string',
        // 'fecha_archivo' => 'datetime',
        'numero_inventario' => 'string',
        'estado' => 'string',
        'observacion' => 'string',
        'classification_serie' => 'integer',
        'classification_subserie' => 'integer',
        'classification_production_office' => 'integer',
        'id_responsable' => 'integer'



    ];

    public static array $rules = [
        'nombre_expediente' => 'nullable|string|max:255',
        'user_name' => 'nullable|string|max:255',
        'nombre_dependencia' => 'nullable|string|max:255',
        'fecha_inicio_expediente' => 'nullable',
        'descripcion' => 'nullable|string',
        'classification_serie' => 'nullable',
        'classification_subserie' => 'nullable',
        'classification_production_office' => 'nullable',
        'existe_fisicamente' => 'nullable|string|max:120',
        'ubicacion' => 'nullable|string|max:255',
        'sede' => 'nullable|string|max:255',
        'area_archivo' => 'nullable|string|max:255',
        'estante' => 'nullable|string|max:255',
        'modulo' => 'nullable|string|max:255',
        'entrepano' => 'nullable|string|max:255',
        'caja' => 'nullable|string|max:255',
        'cuerpo' => 'nullable|string|max:255',
        'unidad_conservacion' => 'nullable|string|max:255',
        'fecha_archivo' => 'nullable',
        'numero_inventario' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:255',
        'observacion' => 'nullable|string',
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

    protected  $appends = [
        'encrypted_id',
        'metadatos',
        'name_responsable',
        'permiso_usar_expediente',
        'permiso_consultar_expediente',
        'permiso_usuarios_expediente',
        'image_responsable'
    ];

    public function expedienteHasMetadatos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\ExpedienteHasMetadato::class, 'ee_expediente_id')->with("metadatos");
    }

    public function getMetadatosAttribute() {
        $metadatos = [];
        $metadatos_value = $this->expedienteHasMetadatos()->get()->toArray();
        for ($i = 0; $i < count($metadatos_value); $i++) {
            // Agregar el valor al array
            $metadatos["{$metadatos_value[$i]['ee_metadatos_id']}"] = $metadatos_value[$i]["valor"];
        }

        return (object) $metadatos;
    }
    /**
     * Encripta el id del expediente
     * @return
     */
    public function getEncryptedIdAttribute() {

        return  base64_encode($this->id);
    }

    /** Obtiene el nombre del responsable actual
    * @return
    */
    public function getNameResponsableAttribute() {

        $nombre_responsable = User::find($this->id_responsable);
        return $nombre_responsable["fullname"] ?? $nombre_responsable->name;
    }

    /**
    * Obtiene el nombre del responsable actual
    * @return
    */
    public function getPermisoUsarExpedienteAttribute() {

        $permiso_uso = Auth::check() ? Expediente::where("estado", "Abierto")->where("id", $this->id)
        ->where(function($q) {
            $q->where("permiso_general_expediente", 'Todas las dependencias pueden incluir y editar documentos en el expediente');
            $q->orWhereHas('eePermisoUsuariosExpedientes', function($query) {
                $query->where(function($n) {
                    // Datos del usuario en sesión
                    $user = Auth::user();
                    // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                    $n->where(function($subQuery) use ($user) {
                        // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                        $subQuery->where('dependencia_usuario_id', $user->id)->where('tipo', 'Usuario');
                    });
                    $n->orWhere(function($subQuery) use ($user) {
                        // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                        $subQuery->where('dependencia_usuario_id', $user->id_dependencia)->where('tipo', 'Dependencia');
                    });
                })->whereIn('permiso', [
                    'Incluir información y editar documentos (solo del usuario)',
                    'Incluir información y editar documentos'
                ]);
            });
        })
        ->count() : 0;
        return $permiso_uso > 0;
    }

    /**
    * Obtiene
    * @return
    */
    public function getPermisoConsultarExpedienteAttribute() {
        $permiso_consulta = Auth::check() ? Expediente::where("estado", "Abierto")->where("id", $this->id)
        ->where(function($q) {
            $q->where("permiso_general_expediente", 'Todas las dependencias están autorizadas para ver información y documentos del expediente');
            $q->orWhereHas('eePermisoUsuariosExpedientes', function($query) {
                $query->where(function($n) {
                    // Datos del usuario en sesión
                    $user = Auth::user();
                    // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                    $n->where(function($subQuery) use ($user) {
                        // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                        $subQuery->where('dependencia_usuario_id', $user->id)->where('tipo', 'Usuario');
                    });
                    $n->orWhere(function($subQuery) use ($user) {
                        // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                        $subQuery->where('dependencia_usuario_id', $user->id_dependencia)->where('tipo', 'Dependencia');
                    });
                })->where('permiso', 'Consultar el expediente y sus documentos');
            });
        })
        ->count() : 0;
        return $permiso_consulta > 0;
    }

    public function getImageResponsableAttribute()
    {
        $nombre_responsable = User::find($this->id_responsable);
        return $nombre_responsable ? $nombre_responsable['url_img_profile'] : null;
    }
    public function dependencias(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    public function eeDocumentosExpedientes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\DocumentosExpediente::class, 'ee_expediente_id')->with('eeTiposDocumentales');
    }

    public function eeExpedienteHistorials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\ExpedienteHistorial::class, 'ee_expediente_id');
    }

    /**
     * Relación con la serie de la clasificación documental
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function serieClasificacionDocumental() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\seriesSubSeries::class, 'classification_serie')->with('CriteriosBusqueda');
    }

    /**
     * Relación con la subserie de la clasificación documental
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subserieClasificacionDocumental() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\seriesSubSeries::class, 'classification_subserie')->with('CriteriosBusqueda');
    }

    /**
     * Relación con la oficina productora de la clasificación documental
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function oficinaProductoraClasificacionDocumental() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'classification_production_office');
    }

    public function eePermisoUsarExpedientesAux(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        $user = Auth::user();

        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\PermisoUsuariosExpediente::class, 'ee_expedientes_id')->with("dependencias_usuarios")
            ->where(function($q) use ($user) {
                $q->where(function($subQuery) use ($user) {
                    // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                    $subQuery->where('dependencia_usuario_id', $user->id)->where('tipo', 'Usuario');
                })->orWhere(function($subQuery) use ($user) {
                    // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                    $subQuery->where('dependencia_usuario_id', $user->id_dependencia)->where('tipo', 'Dependencia');
                });
            });
    }

    public function eePermisoUsuarioExternoExpedientes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\PermisoUsuarioExternoExpediente::class, 'ee_expedientes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function expedienteLeido() {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\ExpedienteLeido::class, 'ee_expediente_id');
    }

    public function eePermisoUsuariosExpedientes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\PermisoUsuariosExpediente::class, 'ee_expedientes_id');
    }

    /**
    * Obtiene los usuarios autorizados, internos y externos para ver expedientes
    * @return
    */
    public function getPermisoUsuariosExpedienteAttribute() {
        $permiso_usuarios = Auth::check() ? Expediente::where("estado", "Abierto")->where("id", $this->id)
        ->where(function($q) {
            $q->where("permiso_general_expediente", 'Todas las dependencias pueden incluir y editar documentos en el expediente');
            $q->where("permiso_general_expediente", 'Todas las dependencias están autorizadas para ver información y documentos del expediente');
            $q->orWhereHas('eePermisoUsuariosExpedientes', function($query) {
                // Datos del usuario en sesión
                $user = Auth::user();
                // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                $query->where(function($subQuery) use ($user) {
                    // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                    $subQuery->where('dependencia_usuario_id', $user->id)->where('tipo', 'Usuario');
                });
                $query->orWhere(function($subQuery) use ($user) {
                    // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                    $subQuery->where('dependencia_usuario_id', $user->id_dependencia)->where('tipo', 'Dependencia');
                });
            });
        })
        ->count() : 0;
        return $permiso_usuarios > 0;
    }

    public function expedienteAnotaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\ExpedienteAnotacion::class, 'ee_expediente_id')->with(["users"])->latest();
    }

    /**
     * Devuelve las anotaciones pendientes para el usuario actual.
     *
     * Este método define una relación para recuperar las anotaciones pendientes
     * asociadas a esta instancia de modelo para el usuario actual.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function anotacionesPendientes()
    {
        // Obtiene el ID del usuario actual autenticado
        $userId = Auth::check() ? Auth::id() : 0;

        return $this->hasMany(\Modules\ExpedientesElectronicos\Models\ExpedienteAnotacion::class, 'ee_expediente_id')
            ->where('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
            ->Where('leido_por', 'not like', $userId . ',%')
            ->where('leido_por', 'not like', '%,' . $userId)
            ->Where('leido_por', 'not like', $userId );
    }
}
