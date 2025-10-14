<?php

namespace Modules\DocumentosElectronicos\Models;

use App\Http\Controllers\JwtController;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class DocumentoFirmar extends Model
{
    public $table = 'de_documento_firmar';

    public $fillable = [
        'tipo_usuario',
        'nombre_usuario',
        'correo',
        'observacion',
        'estado',
        'hash',
        'ip',
        'fecha_firma',
        'url_firma',
        'codigo_acceso_documento',
        'users_id',
        'de_documentos_id',
        'de_documento_version_id'
    ];

    protected $casts = [
        'tipo_usuario' => 'string',
        'nombre_usuario' => 'string',
        'correo' => 'string',
        'observacion' => 'string',
        'estado' => 'string',
        'hash' => 'string',
        'ip' => 'string',
        'fecha_firma' => 'datetime',
        'codigo_acceso_documento' => 'string'
    ];

    public static array $rules = [
        'tipo_usuario' => 'nullable|string|max:80',
        'nombre_usuario' => 'nullable|string|max:200',
        'correo' => 'nullable|string|max:255',
        'observacion' => 'nullable|string',
        'estado' => 'nullable|string|max:90',
        'hash' => 'nullable|string|max:255',
        'ip' => 'nullable|string|max:150',
        'fecha_firma' => 'nullable',
        'codigo_acceso_documento' => 'nullable|string|max:100',
        'users_id' => 'nullable',
        'de_documentos_id' => 'required',
        'de_documento_version_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ["id_encriptado","funcionario_firmar"];

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

    public function getIdEncriptadoAttribute() {
        return JwtController::generateToken($this->id);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    public function deDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\Documento::class, 'de_documentos_id');
    }

    public function deDocumentoVersion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\DocumentoVersion::class, 'de_documento_version_id');
    }

    public function usuarios(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    public function getFuncionarioFirmarAttribute()
    {
        return $this->users_id;
    }
}
