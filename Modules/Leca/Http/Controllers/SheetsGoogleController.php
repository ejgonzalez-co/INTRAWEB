<?php

namespace Modules\Leca\Http\Controllers;

use Auth;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Http\Services\GoogleSheetsServices;

class SheetsGoogleController extends AppBaseController
{

   public function sheetOperation(){

      (new GoogleSheetsServices())->writeSheet([
         [
            'Aditya kadam',
            '1+2@gmail.com'
         ]
      ]);

      $data = (new GoogleSheetsServices())->readSheet();

      return response()->json($data);

   }

}
