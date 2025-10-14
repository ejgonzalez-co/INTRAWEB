<?php

namespace Modules\DocumentosElectronicos\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;

class PermisoCrearDocumento extends Model
{
    public $table = 'de_permiso_crear_documento';

    public $fillable = [
        'nombre',
        'dependencias_id',
        'de_tipos_documentos_id',
        'type_user',
        'cargo_id',
        'grupo_id',
        'usuario_id'
    ];

    protected $casts = [
        'nombre' => 'string'
    ];

    protected $appends = ['recipient_datos'];

    public static array $rules = [
        'nombre' => 'required|string|max:255',
        'dependencias_id' => 'required',
        'de_tipos_documentos_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function deTiposDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\DeTipoDocumento::class, 'de_tipos_documentos_id');
    }

    public function dependencias(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
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
                ->where('dependencias.id',$this->dependencias_id)
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

                if ($users) {
                    $users["nombre"] = $users["fullname"];
                    return $users;
                } else {
                    // Retornar null o un array vacío si no se encuentra el usuario
                    return null; // o return [];
                }
            default:
                return [];
        }
    }
}
