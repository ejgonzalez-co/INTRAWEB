<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackendController extends Controller
{
    //Función que retorna la vista inicial del backend
    public function index(){
        return view('backend.index');
    }
}
