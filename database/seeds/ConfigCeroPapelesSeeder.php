<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Http\Request;

class ConfigCeroPapelesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Request $request)
    {
        // Inserta los roles requeridos por el sistema
        DB::table('roles')->insert([
            'name' => 'Administrador intranet',
            'guard_name' => 'web'
        ]);
        
        DB::table('roles')->insert([
            'name' => 'Administrador intranet de encuestas',
            'guard_name' => 'web'
        ]);
        
        DB::table('roles')->insert([
            'name' => 'Administrador intranet de descargas',
            'guard_name' => 'web'
        ]);
        
        DB::table('roles')->insert([
            'name' => 'Administrador intranet de eventos',
            'guard_name' => 'web'
        ]);
        //--------------------------------------------------
        

        // Relaciona los roles de administración al usuario administrador
        DB::table('model_has_roles')->insert([
            'role_id' => 1, 
            'model_type' => 'App\User', 
            'model_id' => 1
        ]);
        
        DB::table('model_has_roles')->insert([
            'role_id' => 2, 
            'model_type' => 'App\User', 
            'model_id' => 1
        ]);
        
        DB::table('model_has_roles')->insert([
            'role_id' => 3, 
            'model_type' => 'App\User', 
            'model_id' => 1
        ]);
        
        DB::table('model_has_roles')->insert([
            'role_id' => 4, 
            'model_type' => 'App\User', 
            'model_id' => 1
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => 5, 
            'model_type' => 'App\User', 
            'model_id' => 1
        ]);
        //--------------------------------------------------


        // Registra las variables requeridas para la correspondencia
        DB::table('intranet_variables')->insert([
            'name' => 'var_internal_consecutive', 
            'value' => 'Y-DP-PLCO', 
            'type' => 'Normal', 
            'description' => 'Formato Interno de consecutivo de documentos internos'
        ]);

        DB::table('intranet_variables')->insert([
            'name' => 'var_internal_consecutive_prefix', 
            'value' => 'Y-DP-PL', 
            'type' => 'Normal', 
            'description' => 'Formato de prefijo número de consecutivo'
        ]);
        //--------------------------------------------------

        
        // Registra la sede por defecto del administrador
        DB::table('sedes')->insert([
            'nombre' => 'Armenia', 
            'descripcion' => 'Sede de Armenia', 
            'codigo' => 'ARM01'
        ]);
        //--------------------------------------------------


        // Registra la dependencia por defecto del administrador
        DB::table('dependencias')->insert([
            'id_sede' => 1, 
            'codigo' => 'DTIC', 
            'nombre' => 'Dirección de Técnologias de la Información y las Comunicación', 
            'codigo_oficina_productora' => '1300'
        ]);
        //--------------------------------------------------

        
        // Registra el cargo por defecto del administrador
        DB::table('cargos')->insert([
            'nombre' => 'Cargo de administrador', 
            'descripcion' => 'Cargo de administrador'
        ]);
        //--------------------------------------------------


        // Registra los tipos de correspondencia por defecto del sitio
        DB::table('correspondence_internal_type')->insert([
            'name' => 'Oficio', 
            'prefix' => 'OF'
        ]);

        DB::table('correspondence_internal_type')->insert([
            'name' => 'Memorando', 
            'prefix' => 'IM'
        ]);
        //--------------------------------------------------


        User::create([
            'name' => $request['name'], 
            'email' => $request['email'], 
            'email_verified_at' => date("Y-m-d"), 
            'id_cargo' => 1, 
            'id_dependencia' => 1, 
            'password' => Hash::make($request['password'])
        ]);
    }
}
