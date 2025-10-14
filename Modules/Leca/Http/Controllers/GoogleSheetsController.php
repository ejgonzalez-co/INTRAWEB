<?php
namespace Modules\Leca\Http\Controllers;

class GoogleSheetsController extends Controller{

   public function sheetOperation(){

      $data=(new GoogleSheetsServices())->readSheet();


   }

}

?>