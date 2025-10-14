<?php

namespace App\Http\Controllers\GestionUsuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;

use App\Modelo\LogicaGlobal;

class RolController extends Controller
{
    /**
     * Función que retorna la vista inicial con el listado de registros
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $roles = Role::paginate();
        $permissions = Permission::get();
        return view('backend.gestionUsuario.roles.index', compact('roles', 'permissions'));
    }


    //Función que retorna el listado de categorías
    public function listarRoles(Request $request){

        /*$request->query->joins[] = 'roles,id,permission_role,role_id';
        $request->query->joins[] = 'permission_role,permission_id,permissions,id,GROUP_CONCAT(permissions.id) AS permisosId';
        */
        
        //Campos por los cuales se ordena la consulta
        $request->query->orderBy[] = 'roles.created_at DESC';

        //Código que sirve para retornar la consulta a la base de datos
        return $categoriasGeo  = LogicaGlobal::listarRegistrosVue(
                            'roles',
                            $request

        );
    }

    public function listarPermisosRol(Request $request){

        $request->query->joins[] = 'permissions,id,permission_role,permission_id,permission_role.permission_id';

        $request->query->wheres[] = 'AND permission_role.role_id = '.$request->rol;


        $request['rol']= '';

        //Campos por los cuales se ordena la consulta
        $request->query->orderBy[] = 'permissions.created_at DESC';

        
        //Código que sirve para retornar la consulta a la base de datos
        return LogicaGlobal::listarRegistrosVue('permissions,created_at', $request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $permissions = Permission::get();
        return view('backend.gestionUsuario.roles.create', compact('permissions'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //dd($request->get('permissions'));
        $request['slug'] = trim(str_replace(' ', '', mb_strtolower($request->name)));
        $role = Role::create($request->all());
        $role->permissions()->sync($request->get('permissions'));

        return;
        /*return redirect()->route('roles.edit', $role->id)
            ->with('info', 'Rol guardado con éxito');*/
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $role = Role::find($id);
        return view('backend.gestionUsuario.roles.show', compact('role'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $role = Role::find($id);
        $permissions = Permission::get();

        return compact('role', 'permissions');
        //return view('backend.gestionUsuario.roles.edit', compact('role', 'permissions'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $request['slug'] = trim(str_replace(' ', '', mb_strtolower($request->name)));
        $role = Role::find($id);
        $role->update($request->all());
        $role->permissions()->sync($request->get('permissions'));

        return;
        /*return redirect()->route('roles.edit', $role->id)
            ->with('info', 'Rol guardado con éxito');*/
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $role = Role::find($id)->delete();
        return;
        //return back()->with('info', 'Eliminado correctamente');
    }
}
