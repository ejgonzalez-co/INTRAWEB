<?php

namespace Modules\DocumentosElectronicos\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;

class PermisoConsultarDocumento extends Model
{
    public $table = 'de_permiso_consultar_documento';

    public $fillable = [
        'de_tipo_documento_id',
        'name',
        'nombre',
        'type_user',
        'cargo_id',
        'dependencia_id',
        'grupo_id',
        'usuario_id'
    ];

    protected $casts = [
        'nombre' => 'string',
        'type_user' => 'string'
    ];

    protected $appends = ['recipient_datos'];

    public static array $rules = [
        'de_tipo_documento_id' => 'required',
        'nombre' => 'nullable|string|max:255',
        'type_user' => 'nullable|string|max:45',
        'cargo_id' => 'nullable',
        'dependencia_id' => 'nullable',
        'grupo_id' => 'nullable',
        'usuario_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function deTipoDocumento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\DeTipoDocumento::class, 'de_tipo_documento_id');
    }

    public function dependencias(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencia_id');
    }

    public function getRecipientDatosAttribute(){
        switch($this->type_user){
            case "Cargo":
                $position = DB::table('cargos')
                ->select(DB::raw('CONCAT("Cargo ", cargos.nombre) AS nombre, cargos.id as cargos_id, concat("cargo",cargos.id) as id, "Cargo" AS type,"" AS email'))
                ->where('cargos.id',$this->cargo_id)
                ->first();
                return $position;
            case "Dependencia":
                $dependencias = DB::table('dependencias')
                ->select(DB::raw('CONCAT("Dependencia ", dependencias.nombre) AS nombre, dependencias.id as dependencias_id, concat("dependencia",dependencias.id) as id, "Dependencia" AS type, "" AS email'))
                ->where('dependencias.id',$this->dependencia_id)
                ->first();
                return $dependencias;
            case "Grupo":
                $grupos = DB::table('work_groups')
                ->select(DB::raw('CONCAT("Grupo ", work_groups.name) AS nombre, work_groups.id as work_groups_id, concat("grupo",work_groups.id) as id, "Grupo" AS type, "" AS email'))
                ->where('work_groups.id',$this->grupo_id )
                ->first();
                return $grupos;
            case "Usuario":
                $users = User::where('id', $this->usuario_id) // Filtra por nombre que contenga el valor de $query
                ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
                ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
                ->where('id_cargo', '!=', 0)
                ->where('id_dependencia', '!=', 0)
                ->first(); // Realiza la consulta y obtiene una colección de usuarios

                $users["nombre"] = $users["fullname"]; // Accede al mutador para calcular el fullname

                return $users;
            default:
                return [];
        }
    }
}
