<?php

namespace App\Http\Controllers;

use voku\helper\AntiXSS;
use Illuminate\Http\Request;

class AntiXSSController extends Controller
{
    public static function xssClean(Request|array|string $request, ?array $fieldsToSanitize = [])
    {
        $antiXss = new AntiXSS();

        // Validacion por si es un filtro
        if(gettype($request) == "string"){
            $request = $antiXss->xss_clean($request);
        }
        else{
            // Sanitiza los campos especificados
            foreach ($fieldsToSanitize as $field) {
                if (!empty($request[$field])) {
                    $request[$field] = $antiXss->xss_clean($request[$field]);
                }
            }
        }

        return $request;
    }
}