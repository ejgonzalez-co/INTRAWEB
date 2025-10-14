<?php

namespace Modules\DocumentosElectronicos\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    public $table = 'de_tipo_documento';

    public $fillable = [
        'nombre',
        'prefijo',
        'version',
        'codigo_formato',
        'formato_consecutivo',
        'separador_consecutivo',
        'prefijo_incrementan_consecutivo',
        'variables_plantilla',
        'variables_plantilla_requeridas',
        'permiso_crear_documentos_todas',
        'permiso_consultar_documentos_todas',
        'plantilla',
        'preview_document',
        'estado',
        'sub_estados',
        'sub_estados_requerido',
        'vigencia',
        'users_id',
        'es_borrable',
        'es_editable'
    ];

    protected $casts = [
        'nombre' => 'string',
        'prefijo' => 'string',
        'codigo_formato' => 'string',
        'formato_consecutivo' => 'string',
        'separador_consecutivo' => 'string',
        'prefijo_incrementan_consecutivo' => 'string',
        'variables_plantilla' => 'string',
        'variables_plantilla_requeridas' => 'integer',
        'permiso_crear_documentos_todas' => 'integer',
        'plantilla' => 'string',
        'preview_document' => 'string',
        'estado' => 'string',
        'sub_estados' => 'string',
        'sub_estados_requerido' => 'integer'
    ];

    public static array $rules = [
        'nombre' => 'required|string|max:200',
        'prefijo' => 'required|string|max:250',
        'version' => 'nullable',
        'codigo_formato' => 'nullable|string|max:250',
        'separador_consecutivo' => 'nullable|string|max:25',
        'estado' => 'required|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ["formato_consecutivo_value","prefijo_incrementan_consecutivo_value","variables_plantilla_value","sub_estados_value"];

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

    /**
     * Formato del consecutivo
     *
     * @return $formato_consecutivo como un arreglo
     */
    public function getFormatoConsecutivoValueAttribute() {
        return explode(", ", $this->formato_consecutivo);
    }

    /**
     * Prefijo base para el incremento del consecutivo
     *
     * @return $prefijo_incrementan_consecutivo como un arreglo
     */
    public function getPrefijoIncrementanConsecutivoValueAttribute() {
        return explode(", ", $this->prefijo_incrementan_consecutivo);
    }

    /**
     * Variables del tipo de documento
     *
     * @return $variables_plantilla formateado con la clave variable en cada posición del arreglo
     */
    public function getVariablesPlantillaValueAttribute() {
        // Formatea los subestados del tipo de documento separados por coma (,) retornando un arreglo con nombre de clave subestado
        $variables_plantilla = $this->variables_plantilla ? '[["variable" =>'. str_replace(', ', '"],["variable" => "', '"'.$this->variables_plantilla.'"').']]' : '[]';
        return eval("return ".$variables_plantilla.";");
    }

    /**
     * Subestados del tipo de documento
     *
     * @return $sub_estados del tipo de documento
     */
    public function getSubEstadosValueAttribute() {
        // Formatea los subestados del tipo de documento separados por coma (,) retornando un arreglo con nombre de clave subestado
        $sub_estados = $this->sub_estados ? '[["subestado" =>'. str_replace(', ', '"],["subestado" => "', '"'.$this->sub_estados.'"').']]' : '[]';
        return eval("return ".$sub_estados.";");
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\User::class, 'users_id');
    }

    public function deDocumentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\Documento::class, 'de_tipos_documentos_id');
    }

    public function deMetadatos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\Metadato::class, 'de_tipos_documentos_id')->orderBy("orden");
    }

    public function dePermisoCrearDocumentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\PermisoCrearDocumento::class, 'de_tipos_documentos_id')->with("dependencias");
    }
    public function dePermisoConsultarDocumentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\PermisoConsultarDocumento::class, 'de_tipo_documento_id')->with("dependencias");
    }
}
