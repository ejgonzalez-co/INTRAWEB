<?php

namespace App\Http\Controllers\GestionUsuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;

use App\Modelo\LogicaGlobal;

use Session;
use Redirect;

class PermisoController extends Controller
{
   /**
     * Función que retorna la vista inicial con el listado de registros
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permisos = Permission::paginate();
        return view('backend.gestionUsuario.permiso.index', compact('permisos'));
    }

    //Función que retorna el listado de categorías
    public function listarPermisos(Request $request){

    
        //Campos por los cuales se ordena la consulta
        $request->query->orderBy[] = 'created_at DESC';

        //Código que sirve para retornar la consulta a la base de datos
        return $listadoPermisos  = LogicaGlobal::listarRegistrosVue(
            'permissions',
            $request
        );
        /*
        $permisos = Permission::paginate();

        return LogicaGlobal::listadoRegistrosVue($permisos, 'listadoPermisos');*/

        //return response()->json(['listadoPermisos' => $permisos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $permissions = Permission::get();
        return view('backend.gestionUsuario.permiso.create', compact('permissions'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $request['slug'] = trim(str_replace(' ', '', mb_strtolower($request->name)));

        $permiso = Permission::create($request->all());

        //$permisos = Permission::paginate();
        //$role->permissions()->sync($request->get('permissions'));
        //return redirect()->route('permisos.edit', $permiso->id)
          //  ->with('info', 'Rol guardado con éxito');

        return;

        /*Session::flash('success', 'Permiso guardado con éxito');

        return Redirect::to('permisos');*/
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $permiso = Permission::find($id);
        return view('backend.gestionUsuario.permiso.show', compact('permiso'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $permiso = Permission::find($id);
        $permissions = Permission::get();
        return view('backend.gestionUsuario.permiso.edit', compact('permiso', 'permissions'));
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
        $permiso = Permission::find($id);
        $permiso->update($request->all());
        return;
        /*
        //$role->permissions()->sync($request->get('permissions'));
        return redirect()->route('permisos.edit', $permiso->id)
            ->with('info', 'Rol guardado con éxito');*/
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $permiso = Permission::find($id)->delete();

        return;
        /*
        Session::flash('success', 'Permiso eliminado con éxito');

        return Redirect::to('permisos');
*/
        //return back()->with('info', 'Eliminado correctamente');
    }
}
