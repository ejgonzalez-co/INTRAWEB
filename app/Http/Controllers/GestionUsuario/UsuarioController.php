<?php

namespace App\Http\Controllers\GestionUsuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Caffeinated\Shinobi\Models\Role;
use App\User;

//Instancia de la sessión
use Session;


use App\Modelo\LogicaGlobal;

//Instancia del modelo de User

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.gestionUsuario.usuario.index');
    }

    public function listarUsuarios(Request $request){

        if (isset($request->tipoUsuario)) {
            if ($request->tipoUsuario == "Ciudadano") {
                //Se hace join de la tabla ciudadano
                $request->query->joins[] = 'ventanilla_users,id,ventanilla_ciudadano,cf_user_id';
            }else if ($request->tipoUsuario == "Funcionario"){
                $request->query->joins[] = 'ventanilla_users,id,ventanilla_intranet_usuario,userid';
            }

            $request['tipoUsuario'] = null;

        }

        //Campos por los cuales se ordena la consulta
        $request->query->orderBy[] = 'ventanilla_users.registerDate DESC';

        //Código que sirve para retornar la consulta a la base de datos
        return LogicaGlobal::listarRegistrosVue('ventanilla_users', $request);
    }


    public function listarCiudadanos(Request $request){

        //Se hace join de la tabla ciudadano
        $request->query->joins[] = 'ventanilla_ciudadano,cf_user_id,ventanilla_users,cf_id';

        //Campos por los cuales se ordena la consulta
        $request->query->orderBy[] = 'ventanilla_users.registerDate DESC';
        

        //Código que sirve para retornar la consulta a la base de datos
        return LogicaGlobal::listarRegistrosVue('ventanilla_users', $request);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $usuario = User::find($id);
        $roles = Role::get();

        return view('backend.gestionUsuario.usuario.edit', compact('usuario', 'roles'));

        //return compact('role', 'permissions');
        //return view('backend.gestionUsuario.usuario.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   

        //dd("hola");
        $usuario = User::find($id);

        try {

            if ($usuario->update($request->all())) {
                $usuario->roles()->sync($request->get('roles'));

                Session::flash('success', 'Usuario actualizado con éxito'); 
                return redirect()->route('usuarios.edit', $usuario->id);
            }else{
                Session::flash('error', 'Error al actualizar 1');
                return redirect()->route('usuarios.edit', $usuario->id);
            }
        } catch (\Exception $e) {
            //dd($e->getCode());
            if ($e->getCode() == 23000) {
                Session::flash('error', 'Error algún dato del formulario ya existe en el sistema '. $e->getCode());
            }else{
                Session::flash('error', 'Error al actualizar'. $e->getCode());
            }
            
            return redirect()->route('usuarios.edit', $usuario->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();
        return;
    }

    public function generarPdf(Request $request){

        if (isset($request->tipoUsuario)) {
            if ($request->tipoUsuario == "Ciudadano") {
                //Se hace join de la tabla ciudadano
                $request->query->joins[] = 'ventanilla_users,id,ventanilla_ciudadano,cf_user_id';
            }else if ($request->tipoUsuario == "Funcionario"){
                $request->query->joins[] = 'ventanilla_users,id,ventanilla_intranet_usuario,userid';
            }

            $request['tipoUsuario'] = null;

        }
        //Campos por los cuales se ordena la consulta
        $request->query->orderBy[] = 'ventanilla_users.registerDate DESC';

        //Código que sirve para retornar la consulta a la base de datos
        $usuarios = LogicaGlobal::listarRegistrosVue('ventanilla_users', $request);

        $vista = "backend.gestionUsuario.usuario.reporte.usuariosPdf";

        return LogicaGlobal::exportarPdf($vista, compact('usuarios'), 'usuarios');
    }

    public function generarExcel(Request $request){

        if (isset($request->tipoUsuario)) {
            if ($request->tipoUsuario == "Ciudadano") {
                //Se hace join de la tabla ciudadano
                $request->query->joins[] = 'ventanilla_users,id,ventanilla_ciudadano,cf_user_id';
            }else if ($request->tipoUsuario == "Funcionario"){
                $request->query->joins[] = 'ventanilla_users,id,ventanilla_intranet_usuario,userid';
            }

            $request['tipoUsuario'] = null;

        }


    	///Campos por los cuales se ordena la consulta
        $request->query->orderBy[] = 'ventanilla_users.registerDate DESC';

        //Código que sirve para retornar la consulta a la base de datos
        $usuarios = LogicaGlobal::listarRegistrosVue('ventanilla_users', $request);
      
        $vista = "backend.gestionUsuario.usuario.reporte.usuariosExcel"; 
        
        return LogicaGlobal::generarExcel($vista, compact('usuarios'), 'usuarios');
    }
}
