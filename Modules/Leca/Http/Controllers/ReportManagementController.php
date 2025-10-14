<?php
//importaciones 
    namespace Modules\Leca\Http\Controllers;
 
        use App\Exports\GenericExport;
        use Modules\Leca\Http\Requests\CreateReportManagementRequest;
        use Modules\Leca\Http\Requests\UpdateReportManagementRequest;
        use Modules\Leca\Repositories\ReportManagementRepository;
        use App\Http\Controllers\AppBaseController;
        use Illuminate\Http\Request;
        use Flash;
        use Maatwebsite\Excel\Facades\Excel;
        use Response;
        use Auth;
        use DB;
        use Modules\Leca\Models\ReportManagement;
        use Modules\Leca\Models\HistoryReport;
        use Modules\Leca\Models\SampleTaking;
        use Modules\Leca\Models\Customers;
        use Illuminate\Support\Carbon;
        use Illuminate\Support\Facades\Mail;
        use App\Mail\SendMail;
        use Modules\Leca\Models\User;
        use Spatie\Permission\Models\Role;
        use Modules\Leca\Models\ReporManagementAttachment;
        use Modules\Leca\Models\EnsayoTurbidez;
        use Modules\Leca\Models\EnsayoNitratos;
        use Modules\Leca\Models\EnsayoNitritos;
        use Modules\Leca\Models\Aluminio;
        use Modules\Leca\Models\EnsayoPh;
        use Modules\Leca\Models\EnsayoAlcalinidad;
        use Modules\Leca\Models\EnsayoCloro;
        use Modules\Leca\Models\EnsayoAluminio;
        use Modules\Leca\Models\EnsayoConductividad;
        use Modules\Leca\Models\EnsayoColor;
        use Modules\Leca\Models\EnsayoOlor;
        use Modules\Leca\Models\EnsayoSustanciasFlotantes;
        use Modules\Leca\Models\EnsayoCloruro;
        use Modules\Leca\Models\EnsayoDureza;
        use Modules\Leca\Models\EnsayoCalcio;
        use Modules\Leca\Models\EnsayoSulfatos;
        use Modules\Leca\Models\EnsayoHierro;
        use Modules\Leca\Models\EnsayoAcidez;
        use Modules\Leca\Models\EnsayoCarbonoOrganico;
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\IOFactory;

        
    use Modules\Leca\Models\EnsayoFosfato;

    use Modules\Leca\Models\EnsayoMicro;
    use App\Http\Controllers\SendNotificationController;

// end importaciones.

    /**
     * Descripcion de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    class ReportManagementController extends AppBaseController {

    /** @var  ReportManagementRepository */
    private $reportManagementRepository;


    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ReportManagementRepository $reportManagementRepo) {
        $this->reportManagementRepository = $reportManagementRepo;
    }

    /**
     * Muestra la vista para el CRUD de ReportManagement.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('leca::report_managements.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $report_managements=  ReportManagement ::with(['historyReports','SampleTaking','lcCustomers'])->latest()->get();
        return $this->sendResponse($report_managements->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateReportManagementRequest $request
     *
     * @return Response
     */
    public function store(CreateReportManagementRequest $request) {

     $user = Auth::user();
        $input = $request->all();       

        // toma los primeros 10 caracteres de la fecha, para poder ser consultada en base de datos.
        $date= '%'.substr($input['event_date'], 0, 10).'%';
        // consulta multitabla, donde trae los datos si existe relación en los request.

        $consulta = DB::table('lc_sample_taking AS tomaMuestra')
             ->select('tomaMuestra.id as idTomaMuestra', 
                      'cliente.name as nombreCliente',
                      'cliente.id as idCliente',
                      'tomaMuestra.type_water as tipoAgua',
                      'cliente.query_report as medioEntrega',
                      'cliente.email as mailCliente',
                      'tomaMuestra.lc_sample_points_id as idPunto')
             ->join('lc_sample_points AS puntoMuestra','tomaMuestra.lc_sample_points_id','=','puntoMuestra.id')
             ->join('lc_customers_has_lc_sample_points AS puntoCliente','puntoMuestra.id','=','puntoCliente.lc_sample_points_id')
             ->join('lc_customers AS cliente','puntoCliente.lc_customers_id','=','cliente.id')
             ->where('cliente.id',$input['lc_customers_id'])
             ->where('tomaMuestra.created_at','like',$date)
        ->get();
       
        $consulta2= DB::table('lc_sample_taking AS tomaMuestra')
            ->select('tomaMuestra.type_water as tipoAgua')
            ->join('lc_sample_points AS puntoMuestra','tomaMuestra.lc_sample_points_id','=','puntoMuestra.id')
            ->join('lc_customers_has_lc_sample_points AS puntoCliente','puntoMuestra.id','=','puntoCliente.lc_sample_points_id')
            ->join('lc_customers AS cliente','puntoCliente.lc_customers_id','=','cliente.id')
            ->where('cliente.id',$input['lc_customers_id'])
            ->where('tomaMuestra.created_at','like',$date)
            ->distinct()
       ->get();
      
       $relacion=[];
       $validacion=0;
       $validacion1=0;

       //verifica que el cliente tenga una toma de muestra en la fecha seleccionada.
        if ( count($consulta)==0){ 
                return $this->sendSuccess( 'No hay una toma de muestra para el cliente en la fecha especificada', 'error');
               }

       // hay un registro encontrado.
        else if (count($consulta2) == 1){

             // Inicia la transaccion
              DB::beginTransaction();
               try {
             // Inserta el registro en la base de datos
           
        
                $input['user_name'] = $user->name;
                $input['users_id'] = $user->id;
                
                $input['lc_sample_taking_id'] = $consulta[0]->idTomaMuestra;
                $input['lc_customers_id'] = $consulta[0]->idCliente;
                $input['name_customer'] = $consulta[0]->nombreCliente;
                $input['date_report'] = $input['event_date'];
                $input['query_report'] = $consulta[0]->medioEntrega;
                $input['status'] = 'Informe pendiente.';       
                $input['mail_customer'] = $consulta[0]->mailCliente;
                $input['consecutive'] = $this->consecutive($consulta[0],$input)['consecutive'];
                $input['nex_consecutiveIC'] = $this->consecutive($consulta[0],$input)['nex_consecutiveIC'];
                $input['nex_consecutiveIE'] = $this->consecutive($consulta[0],$input)['nex_consecutiveIE'];

              

             $consulta = $this->reportManagementRepository->create($input);
             $consulta -> SampleTaking;

              //crea un objeto de tipo History y lo guarda en la base de datos
                $history = new HistoryReport();
                        
                $history->users_id = $user->id;
                $history->user_name = $user->name;
                $history->status = 'Informe pendiente.';
                $history->lc_rm_report_management_id = $consulta->id;       
                
                $history->save();
                $consulta-> historyReports;
   
                // Efectua los cambios realizados
             DB::commit();

                return $this->sendResponse($consulta, trans('msg_success_save'));                    
            
         } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReportManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
         } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReportManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }  

        
       
        // si son varios registros
        } else if (count($consulta2) != 1) {

             // Inserta el registro en la base de datos
             foreach ($consulta  as $value) {
                if ($value->tipoAgua == 'Cruda') {
                    $validacion++;

                    if ($validacion==1) {
                        $input['user_name'] = $user->name;
                        $input['users_id'] = $user->id;
                        $input['status'] = 'Informe pendiente.'; 
                        $input['lc_sample_taking_id'] = $value->idTomaMuestra;
                        $input['lc_customers_id'] = $value->idCliente;
                        $input['name_customer'] = $value->nombreCliente;
                        $input['date_report'] = $input['event_date'];
                        $input['query_report'] = $value->medioEntrega;
                        $input['mail_customer'] = $value->mailCliente;   
                        $input['consecutive'] = $this->consecutive($value,$input)['consecutive'];
                        $input['nex_consecutiveIC'] = $this->consecutive($value,$input)['nex_consecutiveIC'];
                        $input['nex_consecutiveIE'] = $this->consecutive($value,$input)['nex_consecutiveIE'];
        
                        $value = $this->reportManagementRepository->create($input);

                        //crea un objeto de tipo History y lo guarda en la base de datos
                        $history = new HistoryReport();
                                
                        $history->users_id = $user->id;
                        $history->user_name = $user->name;
                        $history->status = 'Informe pendiente.';
                        $history->lc_rm_report_management_id = $value->id;       
                        
                        $history->save();
                        $relacion=$value;
   
                }

                 }else {
                    $validacion1++;

                    if($validacion1 == 1){

                        $input['user_name'] = $user->name;
                        $input['users_id'] = $user->id;
                        $input['status'] = 'Informe pendiente.'; 
                        $input['lc_sample_taking_id'] = $value->idTomaMuestra;
                        $input['lc_customers_id'] = $value->idCliente;
                        $input['name_customer'] = $value->nombreCliente;
                        $input['date_report'] = $input['event_date'];
                        $input['query_report'] = $value->medioEntrega;
                        $input['mail_customer'] = $value->mailCliente;   
                        $input['consecutive'] = $this->consecutive($value,$input)['consecutive'];
                        $input['nex_consecutiveIC'] = $this->consecutive($value,$input)['nex_consecutiveIC'];
                        $input['nex_consecutiveIE'] = $this->consecutive($value,$input)['nex_consecutiveIE'];
        
                        $value = $this->reportManagementRepository->create($input);
                        
                  
                        
                     //crea un objeto de tipo History y lo guarda en la base de datos
                        $history = new HistoryReport();
                                
                        $history->users_id = $user->id;
                        $history->user_name = $user->name;
                        $history->status = 'Informe pendiente.';
                        $history->lc_rm_report_management_id = $value->id;       
                        
                        $history->save();
                        $relacion=$value;

                        $consulta->lcCustomers;

                        DB::commit();
                            
    
                    }
                
                 }
            }
     
                return $this->sendResponse($relacion, trans('msg_success_save')); 
        }
    }

    /**
     * Funcion encargada de asignar el consecutivo.
     * 
     */
    public function consecutive($value,$input) {
        $Year = '23'; 
         if ( str_contains($value->tipoAgua, "Cruda")){  
           
            $input['nex_consecutiveIE'] = null;
            
             $consecutivoActual = ReportManagement::select(['consecutive', 'nex_consecutiveIC'])->orderBy('id', 'desc')
             ->where('lc_rm_report_management.consecutive','like','%'.'IC'.'%')
             ->get()->first();
             
                 if($consecutivoActual == null ){
                    $input['consecutive'] = "IC-001-".$Year;
                    $input['nex_consecutiveIC'] = 2;
                    return $input;
                
                 }else {                 
                  
                    $consecutivoActual = (int)$consecutivoActual->nex_consecutiveIC;
                    $nConsecutivo=str_pad($consecutivoActual, 3, "0", STR_PAD_LEFT);
                   
                    $input['consecutive'] = "IC-".$nConsecutivo."-".$Year;
                    $input['nex_consecutiveIC'] = $nConsecutivo+1;
                    return $input;

                 }

         } else  {

          $input['nex_consecutiveIC'] = null;

            // trae la cantidad de registros de agua tratada o de proceso.
            $consecutivoActual = ReportManagement::select(['consecutive', 'nex_consecutiveIE'])->orderBy('id', 'desc')
             ->where('lc_rm_report_management.consecutive','like','%'.'IE'.'%')
             ->get()->first();
   
                 if($consecutivoActual == null ){
                    $input['consecutive'] = "IE-001-".$Year;
                    $input['nex_consecutiveIE'] = 2;
                    return $input;
                
                 }else {
                    $consecutivoActual = (int)$consecutivoActual->nex_consecutiveIE;
                    $nConsecutivo=str_pad($consecutivoActual, 3, "0", STR_PAD_LEFT);
                   
                    $input['consecutive'] = "IE-".$nConsecutivo."-".$Year;
                    $input['nex_consecutiveIE'] = $nConsecutivo+1;
                    return $input;
                 }
         }

    }
    
    /**
     * Genera el reporte en excel con el formato leca-r-55
     *
     * 
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getReport($id) {

        
        $array= explode(',',$id );
        $report=$array[0];

        $id=$array[1];

        if ($report == '56') {
            $phat= 'public/leca/report_management/VIG-LECA-R-056.xlsx';
            $nameFile = 'Content-Disposition: attachment;filename="VIG-LECA-R-056.xlsx"';
        }else {
            $phat='public/leca/report_management/VIG-LECA-R-055.xlsx';
            $nameFile = 'Content-Disposition: attachment;filename="VIG-LECA-R-055.xlsx"';
        }
 
      $user = Auth::user();
     
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // consulta para obtener los datos del reporte
        $consulta = DB::table('lc_rm_report_management AS reporte')
             ->select('reporte.consecutive as consecutivo',
                      'reporte.lc_customers_id as cliente',
                      'reporte.status as estado',
                      'date_report as fechaDeToma' )
             ->where('reporte.id',$id)
             ->get()->first();


        $IC = DB::table('lc_sample_taking AS tomaMuestra')
            ->select('tomaMuestra.id as idTomaMuestra',
            'tomaMuestra.reception_date as fechaRecepcion',
                    'tomaMuestra.hour as hora',
                    'tomaMuestra.lc_sample_points_id as punto',
                    'tomaMuestra.sample_reception_code as codigo',
                    'tomaMuestra.user_name as recolectado',
                    'tomaMuestra.created_at as fechaToma',
                    'tomaMuestra.type_water as tipoAgua', 
                    'tomaMuestra.lc_sample_points_id as idPunto',
                    'cliente.name as nombreCliente',
                    'cliente.id as idCliente',  
                    'cliente.query_report as medioEntrega',
                    'cliente.email as mailCliente'
                    )
            ->join('lc_sample_points AS puntoMuestra','tomaMuestra.lc_sample_points_id','=','puntoMuestra.id')
            ->join('lc_customers_has_lc_sample_points AS puntoCliente','puntoMuestra.id','=','puntoCliente.lc_sample_points_id')
            ->join('lc_customers AS cliente','puntoCliente.lc_customers_id','=','cliente.id')
            ->where('cliente.id',$consulta->cliente)
            ->where('tomaMuestra.created_at','like','%'.Carbon::parse($consulta->fechaDeToma)->format('Y-m-d').'%')
            ->where('tomaMuestra.type_water','like','%Cruda%')
            ->where('tomaMuestra.sample_reception_code','not like','%D%')
         ->get();

 

             
        $IE= DB::table('lc_sample_taking AS tomaMuestra')
         ->select('tomaMuestra.id as idTomaMuestra',
         'tomaMuestra.reception_date as fechaRecepcion',
         'tomaMuestra.lc_sample_points_id as punto',
                 'tomaMuestra.hour as hora',
                'tomaMuestra.sample_reception_code as codigo',
                'tomaMuestra.user_name as recolectado',
                'tomaMuestra.created_at as fechaToma',
                'tomaMuestra.type_water as tipoAgua', 
                'tomaMuestra.lc_sample_points_id as idPunto',
                'cliente.name as nombreCliente',
                'cliente.id as idCliente',  
                'cliente.query_report as medioEntrega',
                'cliente.email as mailCliente')
            ->join('lc_sample_points AS puntoMuestra','tomaMuestra.lc_sample_points_id','=','puntoMuestra.id')
            ->join('lc_customers_has_lc_sample_points AS puntoCliente','puntoMuestra.id','=','puntoCliente.lc_sample_points_id')
            ->join('lc_customers AS cliente','puntoCliente.lc_customers_id','=','cliente.id')
            ->where('cliente.id',$consulta->cliente)
            ->where('tomaMuestra.created_at','like','%'.Carbon::parse($consulta->fechaDeToma)->format('Y-m-d').'%')
            ->where('tomaMuestra.type_water','<>','Cruda')
            ->where('tomaMuestra.sample_reception_code','not like','%D%')

         ->get();



        // consulta para obtener los datos del cliente.
        $cliente = DB::table('lc_customers AS cliente')
                ->select('cliente.name as nombre',
                        'cliente.direction as direccion',
                        'cliente.telephone as telefono',
                        'cliente.email as correo',
                        'cliente.description as descripcion',
                        'cliente.contract_number as contrato')
                ->where('cliente.id',$consulta->cliente)
                ->get()->first(); 
                  
        $quantity=ReportManagement::count();

        $fileType = 'Xlsx';
        $storagePath = storage_path($phat);

        // Lee el archivo del storage enviado 
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($storagePath);
        $spreadsheet->setActiveSheetIndex(0);

        if($quantity != 1){
            $spreadsheet->getActiveSheet()->insertNewRowBefore('50', $quantity - 1);
        }

        if ($consulta->estado == 'Informe parcial.'){
            $spreadsheet->getActiveSheet()->setCellValue('H7','INFORME PARCIAL');
        }else{
            $spreadsheet->getActiveSheet()->setCellValue('H7',$consulta->consecutivo);
        }
            $spreadsheet->getActiveSheet()->setCellValue('Q7','Laboratorio de Ensayo de Calidad del Agua');
            $spreadsheet->getActiveSheet()->setCellValue('H12',$cliente->nombre);
            $spreadsheet->getActiveSheet()->setCellValue('H13',$cliente->direccion);
            $spreadsheet->getActiveSheet()->setCellValue('H14',$cliente->telefono);
            $spreadsheet->getActiveSheet()->setCellValue('H15',$cliente->correo);
            $spreadsheet->getActiveSheet()->setCellValue('H16',$cliente->descripcion);
            $spreadsheet->getActiveSheet()->setCellValue('W16',$cliente->contrato);
           
          
        // fecha
            $spreadsheet->getActiveSheet()->setCellValue('AA8', '22' );
            $spreadsheet->getActiveSheet()->setCellValue('AD8',date("m"));


      
        $cellValue=27;
        $celValueSample=12;
        if(str_contains($consulta->consecutivo, "IC")){

            // Ciclo para guardar los datos en sus respectivas celdas
        for ($i=0; $i < count($IC); $i++) {

        //consultas de los ensayos de IC
            $Turbidez=DB::table('lc_ensayo_turbidez as turbidez')-> select(
                DB::raw('ROUND(AVG(turbidez.expresion),4) as resultado'))
                ->where('turbidez.consecutivo',$IC[$i]->codigo)
            ->get();

            $ph=DB::table('lc_ensayo_ph as ph')-> select(
                DB::raw('ROUND(AVG(ph.resultado),4) as resultado'),
                DB::raw('AVG(ph.temperatura_ph) as temperatura'))
                ->where('ph.consecutivo',$IC[$i]->codigo)
            ->get();
        
            $alcalinidad=DB::table('lc_ensayo_alcalinidad as alcalinidad')->select(
                    DB::raw('ROUND(AVG(alcalinidad.resultado),4) as resultado'),
                    DB::raw('AVG(alcalinidad.ph) as ph'))
            ->where('alcalinidad.consecutivo',$IC[$i]->codigo)
            ->get();

            $cloro=DB::table('lc_ensayo_cloro as cloro')-> select(
                DB::raw('ROUND(AVG(cloro.resultado),4) as resultado'))
                ->where('cloro.consecutivo',$IC[$i]->codigo)
            ->get();

            $aluminio=DB::table('lc_ensayo_aluminio as aluminio')-> select(
                DB::raw('ROUND(AVG(aluminio.resultado),4) as resultado'))
                ->where('aluminio.consecutivo',$IC[$i]->codigo)
            ->get();

            $conductividad=DB::table('lc_ensayo_conductividad as conductividad') ->select(
                DB::raw('conductividad.conductividad  as resultado'),
                DB::raw('AVG(conductividad.temperatura_con) as temperatura'))
                ->where('conductividad.consecutivo',$IC[$i]->codigo)
            ->get();

            $color=DB::table('lc_ensayo_color as color')-> select( 
                DB::raw('color.color as resultado'))
                ->where('color.consecutivo',$IC[$i]->codigo)
            ->get();
            
            $olor=DB::table('lc_ensayo_olor as olor')-> select( 
                DB::raw('ROUND(AVG(olor.olor),4) as resultado'))
                ->where('olor.consecutivo',$IC[$i]->codigo)
            ->get();

            $sustancias=DB::table('lc_ensayo_sustancias_flotantes as sustancias')-> select( 
                DB::raw('(sustancias.sustanciasFlotantes) as resultado'))
                ->where('sustancias.consecutivo',$IC[$i]->codigo)
            ->get();

            $cloruro=DB::table('lc_ensayo_cloruro as cloruro')-> select( 
                DB::raw('ROUND(AVG(cloruro.resultado),4) as resultado'))
                ->where('cloruro.consecutivo',$IC[$i]->codigo)
            ->get();

            $dureza=DB::table('lc_ensayo_dureza as dureza')-> select( 
                DB::raw('ROUND(AVG(dureza.resultado),4) as resultado'))
                ->where('dureza.consecutivo',$IC[$i]->codigo)
            ->get();

            $calcio=DB::table('lc_ensayo_calcio as calcio')-> select( 
                DB::raw('ROUND(AVG(calcio.resultado),4) as resultado'))
                ->where('calcio.consecutivo',$IC[$i]->codigo)
            ->get();

            $sulfato=DB::table('lc_ensayo_sulfatos as sulfato')
            -> select( 
                DB::raw('ROUND(AVG(sulfato.sulfato_resultado_a),4) as resultado'))
                ->where('sulfato.consecutivo',$IC[$i]->codigo)
            ->get();

            $hierro=DB::table('lc_ensayo_hierro as hierro')-> select( 
                DB::raw('ROUND(AVG(hierro.resultado),4) as resultado'))
                ->where('hierro.consecutivo',$IC[$i]->codigo)
            ->get();
            
            $nitritos=DB::table('lc_ensayo_nitritos as nitritos')-> select( 
                DB::raw('ROUND(AVG(nitritos.resultado),4) as resultado'))
                ->where('nitritos.consecutivo',$IC[$i]->codigo)
            ->get();

            $fosfato=DB::table('lc_ensayo_fosfato as fosfato')-> select( 
                DB::raw('ROUND(AVG(fosfato.resultado),4) as resultado'))
                ->where('fosfato.consecutivo',$IC[$i]->codigo)
            ->get();

            $nitratos=DB::table('lc_ensayo_nitratos as nitratos')-> select( 
                DB::raw('ROUND(AVG(nitratos.resultado),4) as resultado'))
                ->where('nitratos.consecutivo',$IC[$i]->codigo)
            ->get();

            $heterotroficas=DB::table('lc_ensayos_microbiologicos as heterotroficos')-> select( 
                DB::raw('ROUND(AVG(heterotroficos.resultado),4) as resultado'))
                ->where('heterotroficos.consecutivo',$IC[$i]->codigo)
            ->get();

        //

                
                $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue,date("d"));
                $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue,Carbon::parse($IC[$i]->fechaToma)->format('d'));
                $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue,Carbon::parse($IC[$i]->fechaToma)->format('h:i'));
                $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue,$IC[$i]->idPunto);
                $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue,$IC[$i]->codigo);
                $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue,Carbon::parse($IC[$i]->fechaRecepcion)->format('d'));
                $s = "Recolectada por\n(".$IC[$i]->codigo.")";
                $spreadsheet->getActiveSheet()->setCellValue('X'.$celValueSample, $s);
                $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue,Carbon::parse($IC[$i]->fechaToma)->format('d'));

                
            // celdas de turbidez
                if (isset($Turbidez[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue,$Turbidez[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue,'NA');
                }
            //    

            // celdas de ph
                if (isset($ph[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue,$ph[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue,'NA');
                }


                if (isset($ph[$i]->temperatura)) {
                    $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue,$ph[$i]->temperatura);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue,'NA');
                }
            //

            //celdas de Alcalinidad
                if (isset($alcalinidad[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue,$alcalinidad[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue,'NA');
                }

                if (isset($alcalinidad[$i]->ph)) {
                    $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue,$alcalinidad[$i]->ph);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue,'NA');
                }
            //    

            //celdas de Cloro
                if (isset($cloro[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue,$cloro[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue,'NA');
                }
            //    

            //celdas de aluminio
                if (isset($aluminio[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue,$aluminio[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue,'NA');
                }
            //    

            //celdas de conductividad
                if (isset($conductividad[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue,$conductividad[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue,'NA');
                }

                if (isset($conductividad[$i]->temperatura)) {
                    $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue,$conductividad[$i]->temperatura);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue,'NA');
                }
            //    

            //celdas de color
                if (isset($color[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('T'.$cellValue,$color[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('T'.$cellValue,'NA');
                }
            //    

            //celdas de color
                if (isset($olor[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('U'.$cellValue,$olor[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('U'.$cellValue,'NA');
                }
            //   
            
            //celdas de sustancias
                if (isset($sustancias[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('V'.$cellValue,$sustancias[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('V'.$cellValue,'NA');
                }
            // 

            //celdas de CLORURO
                if (isset($cloruro[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('W'.$cellValue,$cloruro[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('W'.$cellValue,'NA');
                }
            // 

            //celdas de dureza
                if (isset($dureza[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('X'.$cellValue,$dureza[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('X'.$cellValue,'NA');
                }
            // 

            //celdas de calcio
                if (isset($calcio[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('Y'.$cellValue,$calcio[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('Y'.$cellValue,'NA');
                }
            // 

            //celdas de cloro
                if (isset($cloro[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('Z'.$cellValue,$cloro[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('Z'.$cellValue,'NA');
                }
            // 

            //celdas de sulfatos
                if (isset($sulfato[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('AA'.$cellValue,$sulfato[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('AA'.$cellValue,'NA');
                }
            // 

            //celdas de Hierro
                if (isset($hierro[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('AB'.$cellValue,$hierro[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('AB'.$cellValue,'NA');
                }
            // 

            //celdas de nitritos
                if (isset($nitritos[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('AC'.$cellValue,$nitritos[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('AC'.$cellValue,'NA');
                }
            // 

            //celdas de fosfato
                if (isset($fosfato[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('AD'.$cellValue,$fosfato[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('AD'.$cellValue,'NA');
                }
            // 

            //celdas de nitratos
                if (isset($nitratos[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('AE'.$cellValue,$nitratos[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('AE'.$cellValue,'NA');
                }
            // 

            //celdas de heterotroficas
                if (isset($heterotroficas[$i]->resultado)) {
                    $spreadsheet->getActiveSheet()->setCellValue('AH'.$cellValue,$heterotroficas[$i]->resultado);
                }else {
                    $spreadsheet->getActiveSheet()->setCellValue('AH'.$cellValue,'NA');
                }
        // 

      
           

            $spreadsheet->getActiveSheet()->setCellValue('AC'.$celValueSample,$IC[$i]->recolectado ? $IC[$i]->recolectado : 'NOMBRE NO REGISTRADO');
            $cellValue++;
            $celValueSample++;
           
     
        }
        } else {
        for ($i=0; $i < count($IE); $i++) {

        //consultas de los ensayos de IC
            $Turbidez=DB::table('lc_ensayo_turbidez as turbidez')-> select(
                DB::raw('ROUND(AVG(turbidez.expresion),2) as resultado'))
                ->where('turbidez.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $ph=DB::table('lc_ensayo_ph as ph')-> select(
                DB::raw('ROUND(AVG(ph.resultado),2) as resultado'),
                DB::raw('AVG(ph.temperatura_ph) as temperatura'))
                ->where('ph.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();
        
            $alcalinidad=DB::table('lc_ensayo_alcalinidad as alcalinidad')->select(
                    DB::raw('ROUND(AVG(alcalinidad.resultado),2) as resultado'),
                    DB::raw('AVG(alcalinidad.ph) as ph'))
            ->where('alcalinidad.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $cloro=DB::table('lc_ensayo_cloro as cloro')-> select(
                DB::raw('ROUND(AVG(cloro.resultado),2) as resultado'))
                ->where('cloro.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $aluminio=DB::table('lc_ensayo_aluminio as aluminio')-> select(
                DB::raw('aluminio.resultado as resultado'))
                ->where('aluminio.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $conductividad=DB::table('lc_ensayo_conductividad as conductividad') ->select(
                DB::raw('conductividad.conductividad as resultado'),
                DB::raw('conductividad.temperatura_con as temperatura'))
                ->where('conductividad.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $color=DB::table('lc_ensayo_color as color')-> select( 
                DB::raw('color.color as resultado'))
                ->where('color.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();
            
            $olor=DB::table('lc_ensayo_olor as olor')-> select( 
                DB::raw('olor.olor as resultado'))
                ->where('olor.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $sustancias=DB::table('lc_ensayo_sustancias_flotantes as sustancias')-> select( 
                DB::raw('(sustancias.sustanciasFlotantes) as resultado'))
                ->where('sustancias.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $cloruro=DB::table('lc_ensayo_cloruro as cloruro')-> select( 
                DB::raw('ROUND(AVG(cloruro.resultado),4) as resultado'))
                ->where('cloruro.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $dureza=DB::table('lc_ensayo_dureza as dureza')-> select( 
                DB::raw('ROUND(AVG(dureza.resultado),4) as resultado'))
                ->where('dureza.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $calcio=DB::table('lc_ensayo_calcio as calcio')-> select( 
                DB::raw('ROUND(AVG(calcio.resultado),4) as resultado'))
                ->where('calcio.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $sulfato=DB::table('lc_ensayo_sulfatos as sulfato')
            -> select( 
                DB::raw('ROUND(AVG(sulfato.sulfato_resultado_a),4) as resultado'))
                ->where('sulfato.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $hierro=DB::table('lc_ensayo_hierro as hierro')-> select( 
                DB::raw('ROUND(AVG(hierro.resultado),4) as resultado'))
                ->where('hierro.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();
            
            $nitritos=DB::table('lc_ensayo_nitritos as nitritos')-> select( 
                DB::raw('ROUND(AVG(nitritos.resultado),4) as resultado'))
                ->where('nitritos.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $fosfato=DB::table('lc_ensayo_fosfato as fosfato')-> select( 
                DB::raw('ROUND(AVG(fosfato.resultado),4) as resultado'))
                ->where('fosfato.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();
          
           
            $nitratos=DB::table('lc_ensayo_nitratos as nitratos')-> select( 
                DB::raw('ROUND(AVG(nitratos.resultado),4) as resultado'))
                ->where('nitratos.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();

            $heterotroficas=DB::table('lc_ensayos_microbiologicos as heterotroficos')-> select( 
                DB::raw('ROUND(AVG(heterotroficos.resultado),4) as resultado'))
                ->where('heterotroficos.consecutivo','like','%'.$IE[$i]->codigo.'%')
            ->get();
        //

            $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue,date("d"));
            $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue,Carbon::parse($IE[$i]->fechaToma)->format('d'));
            $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue,Carbon::parse($IE[$i]->fechaToma)->format('h:i'));
            $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue,$IE[$i]->idPunto);
            $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue,$IE[$i]->codigo);
            $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue,Carbon::parse($IE[$i]->fechaRecepcion)->format('d'));
            $s = "Recolectada por\n(".$IE[$i]->codigo.")";
            $spreadsheet->getActiveSheet()->setCellValue('X'.$celValueSample, $s);
            $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue,Carbon::parse($IE[$i]->fechaToma)->format('d'));
           
               
        // celdas de turbidez
            if (isset($Turbidez[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue,$Turbidez[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue,'NA');
            }
        //    

        // celdas de ph
            if (isset($ph[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue,$ph[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue,'NA');
            }


            if (isset($ph[$i]->temperatura)) {
                $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue,$ph[$i]->temperatura);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue,'NA');
            }
        //

        //celdas de Alcalinidad
            if (isset($alcalinidad[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue,$alcalinidad[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue,'NA');
            }

            if (isset($alcalinidad[$i]->ph)) {
                $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue,$alcalinidad[$i]->ph);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue,'NA');
            }
        //    

        //celdas de Cloro
            if (isset($cloro[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue,$cloro[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue,'NA');
            }
        //    

        //celdas de aluminio
            if (isset($aluminio[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue,$aluminio[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue,'NA');
            }
        //    

        //celdas de conductividad
            if (isset($conductividad[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue,$conductividad[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue,'NA');
            }

            if (isset($conductividad[$i]->temperatura)) {
                $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue,$conductividad[$i]->temperatura);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue,'NA');
            }
        //    

        //celdas de color
            if (isset($color[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('T'.$cellValue,$color[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('T'.$cellValue,'NA');
            }
        //    

        //celdas de color
            if (isset($olor[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('U'.$cellValue,$olor[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('U'.$cellValue,'NA');
            }
        //   
        
        //celdas de sustancias
            if (isset($sustancias[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('V'.$cellValue,$sustancias[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('V'.$cellValue,'NA');
            }
        // 

        //celdas de CLORURO
            if (isset($cloruro[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('W'.$cellValue,$cloruro[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('W'.$cellValue,'NA');
            }
        // 

        //celdas de dureza
            if (isset($dureza[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('X'.$cellValue,$dureza[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('X'.$cellValue,'NA');
            }
        // 

        //celdas de calcio
            if (isset($calcio[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('Y'.$cellValue,$calcio[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('Y'.$cellValue,'NA');
            }
        // 

        //celdas de cloro
            if (isset($cloro[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('Z'.$cellValue,$cloro[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('Z'.$cellValue,'NA');
            }
        // 

        //celdas de sulfatos
            if (isset($sulfato[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('AA'.$cellValue,$sulfato[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('AA'.$cellValue,'NA');
            }
        // 

        //celdas de Hierro
            if (isset($hierro[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('AB'.$cellValue,$hierro[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('AB'.$cellValue,'NA');
            }
        // 

        //celdas de nitritos
            if (isset($nitritos[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('AC'.$cellValue,$nitritos[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('AC'.$cellValue,'NA');
            }
        // 

        //celdas de fosfato
            if (isset($fosfato[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('AD'.$cellValue,$fosfato[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('AD'.$cellValue,'NA');
            }
        // 

        //celdas de nitratos
            if (isset($nitratos[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('AE'.$cellValue,$nitratos[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('AE'.$cellValue,'NA');
            }
        // 

        //celdas de heterotroficas
            if (isset($heterotroficas[$i]->resultado)) {
                $spreadsheet->getActiveSheet()->setCellValue('AH'.$cellValue,$heterotroficas[$i]->resultado);
            }else {
                $spreadsheet->getActiveSheet()->setCellValue('AH'.$cellValue,'NA');
            }
        // 

            $spreadsheet->getActiveSheet()->setCellValue('AC'.$celValueSample,$IE[$i]->recolectado ? $IE[$i]->recolectado : 'NOMBRE NO REGISTRADO');
            $cellValue++;
            $celValueSample++;
         }
            
        }
        //Configuraciones de los encabezados del archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header($nameFile);
        header('Cache-Control: max-age=0');

        // Exportacion del archivo
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
        $writer->save('php://output');
        exit;

        return $this->sendResponse($writer, trans('msg_success_update'));
    }

    /**
     * Obtiene los clientes que se encuentra activos.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCustomer() {
        $customer = Customers::where('state', 1)->get();
        return $this->sendResponse($customer->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateReportManagementRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReportManagementRequest $request) {

            $input = $request->all();

        /** @var ReportManagement $reportManagement */
            $reportManagement = $this->reportManagementRepository->find($id);

        if (empty($reportManagement)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
        
            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Actualiza el registro
                $reportManagement = $this->reportManagementRepository->update($input, $id);

                // Efectua los cambios realizados
            DB::commit();
        
                return $this->sendResponse($reportManagement->toArray(), trans('msg_success_update'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReportManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReportManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ReportManagement del almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var ReportManagement $reportManagement */
        $reportManagement = $this->reportManagementRepository->find($id);

        if (empty($reportManagement)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $reportManagement->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReportManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReportManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('report_managements').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName);
        }
    }

    /**
     * Actualiza el estado del informe y envia el correo, notificando
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function changeStatusReport(Request $request) {
        $input = $request->all();
        $user = Auth::user();

       $reportManagement= ReportManagement:: where('id', $input['id'])->first();

       $reportManagement->status=$input['status'];
       $reportManagement->save();
        
       DB::commit();

        //crea un objeto de tipo History y lo guarda en la base de datos
        $history = new HistoryReport();          
            
        $history->created_at = $reportManagement->updated_at;
        $history->users_id = $user->id;
        $history->user_name = $user->name;
        $history->status = $reportManagement->status;
        $history->lc_rm_report_management_id = $reportManagement->id;
        $history->save();
        $reportManagement-> historyReports;
        
         //Busca el correo del cliente que tiene medio de entrega mail
        $customer= Customers::select('name','email')->where('id',$reportManagement->lc_customers_id)
                            ->where('query_report','like','%mail%')->get()->first();

        // $message="Se cambio el estado correctamente"; 
        //  condicion que valida si se envia a un corero o no.
        if(isset($customer)){

            //verifica si el estado es finalizado
             if($reportManagement->status == "Informe finalizado."){


                //Este es el asunto del correo
                $custom = json_decode('{"subject": "Notificación de informe de caracterización del agua."}');     
                //aca se envia el email a el correo del cliente
                // Mail::to($customer->email)->send(new SendMail('leca::report_managements.email.email_notification_customer',$reportManagement, $custom));
                SendNotificationController::SendNotification('leca::report_managements.email.email_notification_customer',$custom,$reportManagement,$customer->email,'Leca');

                //verifica si el estado del informe esta en parcial
         // return $this->sendSuccess('' , 'info');

           } elseif ($reportManagement->status == "Informe parcial.") {
                 //Este es el asunto del correo
                 $custom = json_decode('{"subject": "Notificación de informe parcial de caracterización del agua."}');     
                 //aca se envia el email a el correo del cliente
                 
                // Mail::to($customer->email)->send(new SendMail('leca::report_managements.email.email_change_status_report_parcial',$reportManagement, $custom));
                SendNotificationController::SendNotification('leca::report_managements.email.email_change_status_report_parcial',$custom,$reportManagement,$customer->email,'Leca');

 
             }
             $message="se cambio el estado correctamente y se envio al correo electrónico  ".$customer->email;
        }   
        
       return $this->sendResponse($reportManagement->toArray(), trans('msg_success_save'));
       //return $this->sendResponse($reportManagement->toArray(), $message, 'success');

    }

    /**
     * descarga el reporte LECA-R-14 O LECA-R-33
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getDailyReport($id) {


        $user = Auth::user();  

        $array= explode(',',$id);
        $fecha = $array[0];
        $tipo_Reporte= $array[1];

        // Formato de fecha (AA-MM-DD)
        $fecha_reporte = strtotime(date($fecha));
        
      

        //consulta todos los ensayos
        $consecutivos=  DB::select(" SELECT consecutivo, created_at, user_name FROM(
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_nitritos             where created_at LIKE '%$fecha%') UNION 
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_nitratos             where created_at LIKE '%$fecha%') UNION 
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_turbidez             where created_at LIKE '%$fecha%') UNION 
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_ph                   where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_alcalinidad          where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_cloro                where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_aluminio             where created_at LIKE '%$fecha%') UNION 
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_conductividad        where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_color                where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_olor                 where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_sustancias_flotantes where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_cloruro              where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_dureza               where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_calcio               where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_sulfatos             where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_hierro               where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_fosfato              where created_at LIKE '%$fecha%') UNION
              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_carbono_organico     where created_at LIKE '%$fecha%') UNION

              (SELECT consecutivo, created_at, user_name FROM lc_ensayo_acidez               where created_at LIKE '%$fecha%')    
                  ) as total GROUP BY consecutivo  
                  ORDER BY  CASE
                    WHEN SUBSTR(`total`.`consecutivo`, 1, 1) = 'B' THEN 1
                    WHEN SUBSTR(`total`.`consecutivo`, 1, 1) = 'S' THEN 2
                    ELSE 3
                END");

        $array = [];
        $analistas=[];

        //recorre los analistas y los guarda en un arreglo
        foreach ($consecutivos as $key => $value) {
            $array[$key]=$value->consecutivo; 
            $analistas[$key]=$value->user_name; 
        }

        //Elimina los ánalistas repetidos
        $analistas = array_unique($analistas);

        //muestra el rol del usuario en sesión
        $rol= DB:: table('cargos')->select('nombre')->where('id',$user->id_cargo )->get()->first();

            // si se recibe 14, se genera el reporte 014
        if ($tipo_Reporte == '14') {
            // datos para libreria
                $quantity=ReportManagement::count();
                $fileType = 'Xlsx';
                $storagePath = storage_path('public/leca/report_management/VIG-LECA-R-014.xlsx');
                $cellValue=16;// desde la fila donde se empezara a renderizar.

                //lee la plantilla
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
                $spreadsheet = $reader->load($storagePath);
                // Invoca la función que genera la página 2 del reporte 014
                self::getReporte014Pag2($spreadsheet, $fecha);
                //se pocisiona en la hoja 1 del archivo excel.
                $spreadsheet->setActiveSheetIndex(0);
                

                if ($user->url_digital_signature) {
                     //tratado para agregar la firma del usuario en sesión.              
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath(storage_path( 'app/public/'.$user->url_digital_signature)); /* put your path and image here */
                    $drawing->setCoordinates('J23');
                    $drawing->setWorksheet($spreadsheet->getActiveSheet());
                    $drawing->setHeight(36);
                    $drawing->setResizeProportional(true);
                    $drawing->setOffsetX(2); // this is how
                    $drawing->setOffsetY(2);
                }
              


                if($quantity != 1){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore('50', $quantity - 1);
                }
            // fin  datos para libreria

         
            
            // Escribe la fecha (AA-MM-DD)
            $spreadsheet->getActiveSheet()->setCellValue('W8', date("y", $fecha_reporte));
            $spreadsheet->getActiveSheet()->setCellValue('X8', date("m", $fecha_reporte));
            $spreadsheet->getActiveSheet()->setCellValue('Y8', date("d", $fecha_reporte));
            $spreadsheet->getActiveSheet()->setCellValue('J25',$user->name);
            $spreadsheet->getActiveSheet()->setCellValue('J26',$rol->nombre);
            $spreadsheet->getActiveSheet()->setCellValue('C25',implode("-", $analistas));

            $bk = 0;
            $std = 0;
            $analistas=[];
            $cellValue=16;
            //recorre los ensayos y llena los campos
            foreach ($array as  $value) {

                if (str_contains($value, 'STD')) {
                    if ($std == 0) {
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,'STD'); //genera las columnas con los consecutivos.
                        $std ++;
                    }else {
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,'STD-'.$std); //genera las columnas con los consecutivos.
                        $std ++;
                    }
                    
                }
                else if  (str_contains($value, 'BK')) {
                    if ($bk == 0) {
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,'BK'); //genera las columnas con los consecutivos.
                        $bk ++;
                    }else {
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,'BK-'.$bk); //genera las columnas con los consecutivos.
                        $bk ++;
                    }
                }
                else {
                    $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,$value); //genera las columnas con los consecutivos.
                }
          


                //genera las 2 consultas para turbidez
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $turbidez= EnsayoTurbidez::where('consecutivo',$value)->where('lc_ensayo_turbidez.created_at','like','%'.$fecha.'%')->get()->toArray();
                        $turbidezResult = isset($turbidez[0]['expresion'])  ? $turbidez[0]['expresion']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue ,$turbidezResult);
                        
                    }else {
                        $turbidez= EnsayoTurbidez::select('expresion', 'consecutivo')->where('consecutivo', $value)->where('lc_ensayo_turbidez.created_at','like','%'.$fecha.'%')
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $turbidezResult = isset($turbidez[0]['expresion'])  ? $turbidez[0]['expresion']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue ,$turbidezResult);
                    }
                //fin consulta turbidez

                //genera las 2 consultas para ph
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $ph= EnsayoPh::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        // array_push($analistas,$ph[0]['user_name']);
                        $phResult = isset($ph[0]['resultado'])  ? $ph[0]['resultado']: "NA";
                        $phResultT = isset($ph[0]['temperatura_ph'])  ? $ph[0]['temperatura_ph']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue ,$phResult);
                        $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue ,$phResultT);


                    }else {
                        $ph= EnsayoPh::select('resultado', 'consecutivo','temperatura_ph')->where('consecutivo', $value)->where('lc_ensayo_ph.created_at','like','%'.$fecha.'%')
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $phResultT = isset($ph[0]['temperatura_ph'])  ? $ph[0]['temperatura_ph']: "NA";
                        $phResult = isset($ph[0]['resultado'])  ? $ph[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue ,$phResult);
                        $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue ,$phResultT);
                    }
                //fin consulta ph

                //genera las 2 consultas para alcalinidad
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $alcalinidad= EnsayoAlcalinidad::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $alcalinidadResult = isset($alcalinidad[0]['resultado'])  ? $alcalinidad[0]['resultado']: "NA";
                        $alcalinidadResultT = isset($alcalinidad[0]['ph'])  ? $alcalinidad[0]['ph']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue ,$alcalinidadResult);
                        $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue ,$alcalinidadResultT);

                    }else {
                        $alcalinidad= EnsayoAlcalinidad::select('resultado', 'consecutivo','ph')->where('lc_ensayo_alcalinidad.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $alcalinidadResultT = isset($alcalinidad[0]['ph'])  ? $alcalinidad[0]['ph']: "NA";
                        $alcalinidadResult = isset($alcalinidad[0]['resultado'])  ? $alcalinidad[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue ,$alcalinidadResult);
                        $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue ,$alcalinidadResultT);
                    }
                //fin consulta alcalinidad.

                //genera las 2 consultas para cloro
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $cloro= EnsayoCloro::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($cloro[0]['resultado'])  ? $cloro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue ,$nitritoResult);

                    }else {
                        $cloro= EnsayoCloro::select('resultado', 'consecutivo')->where('lc_ensayo_cloro.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($cloro[0]['resultado'])  ? $cloro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue ,$nitritoResult);
                    }
                //fin consulta cloro.

                //genera las 2 consultas para aluminio
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $aluminio= EnsayoAluminio::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($aluminio[0]['resultado'])  ? $aluminio[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue ,$nitritoResult);

                    }else {
                        $aluminio= EnsayoAluminio::select('resultado', 'consecutivo')->where('lc_ensayo_aluminio.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($aluminio[0]['resultado'])  ? $aluminio[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue ,$nitritoResult);
                    }
                //fin consulta aluminio.

                //genera las 2 consultas para conductividad
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $conductividad= EnsayoConductividad::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $conductividadResult = isset($conductividad[0]['conductividad'])  ? $conductividad[0]['conductividad']: "NA";
                        $conductividadResultT = isset($conductividad[0]['temperatura_con'])  ? $conductividad[0]['temperatura_con']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue ,$conductividadResult);
                        $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue ,$conductividadResultT);

                    }else {
                        $conductividad= EnsayoConductividad::select('conductividad', 'consecutivo','temperatura_con')->where('lc_ensayo_conductividad.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $conductividadResultT = isset($conductividad[0]['temperatura_con'])  ? $conductividad[0]['temperatura_con']: "NA";
                        $conductividadResult = isset($conductividad[0]['conductividad'])  ? $conductividad[0]['conductividad']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue ,$conductividadResult);
                        $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue ,$conductividadResultT);
                    }
                //fin consulta conductividad.

                //genera las 2 consultas para color
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $color= EnsayoColor::where('consecutivo',$value)->where('lc_ensayo_color.created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($color[0]['color'])  ? $color[0]['color']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue ,$nitritoResult);

                    }else {
                        $color= EnsayoColor::select('color', 'consecutivo')->where('lc_ensayo_color.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($color[0]['color'])  ? $color[0]['color']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue ,$nitritoResult);
                    }
                //fin consulta color.

                //genera las 2 consultas para olor sustanciasFlotantes
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $olor= EnsayoOlor::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($olor[0]['olor'])  ? $olor[0]['olor']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue ,$nitritoResult);

                    }else {
                        $olor= EnsayoOlor::select('olor', 'consecutivo')->where('lc_ensayo_olor.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($olor[0]['olor'])  ? $olor[0]['olor']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue ,$nitritoResult);
                    }
                //fin consulta olor.

                //genera las 2 consultas para sustanciasFlotantes sustanciasFlotantes
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $sustanciasFlotantes= EnsayosustanciasFlotantes::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($sustanciasFlotantes[0]['sustanciasFlotantes'])  ? $sustanciasFlotantes[0]['sustanciasFlotantes']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue ,$nitritoResult);

                    }else {
                        $sustanciasFlotantes= EnsayosustanciasFlotantes::select('sustanciasFlotantes', 'consecutivo')->where('lc_ensayo_sustancias_flotantes.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($sustanciasFlotantes[0]['sustanciasFlotantes'])  ? $sustanciasFlotantes[0]['sustanciasFlotantes']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue ,$nitritoResult);
                    }
                //fin consulta sustanciasFlotantes.

                //genera las 2 consultas para cloruro
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $cloruro= EnsayoCloruro::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($cloruro[0]['resultado'])  ? $cloruro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue ,$nitritoResult);

                    }else {
                        $cloruro= EnsayoCloruro::select('resultado', 'consecutivo')->where('lc_ensayo_cloruro.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($cloruro[0]['resultado'])  ? $cloruro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue ,$nitritoResult);
                    }
                //fin consulta cloruro.

                //genera las 2 consultas para dureza
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $dureza= EnsayoDureza::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($dureza[0]['resultado'])  ? $dureza[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue ,$nitritoResult);

                    }else {
                        $dureza= EnsayoDureza::select('resultado', 'consecutivo')->where('lc_ensayo_dureza.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($dureza[0]['resultado'])  ? $dureza[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue ,$nitritoResult);
                    }
                //fin consulta dureza.

                //genera las 2 consultas para calcio
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $calcio= EnsayoCalcio::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($calcio[0]['resultado'])  ? $calcio[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue ,$nitritoResult);

                    }else {
                        $calcio= EnsayoCalcio::select('resultado', 'consecutivo')->where('lc_ensayo_calcio.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($calcio[0]['resultado'])  ? $calcio[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue ,$nitritoResult);
                    }
                //fin consulta calcio.

                //genera las 2 consultas para cloro
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $cloro= EnsayoCloro::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($cloro[0]['resultado'])  ? $cloro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue ,$nitritoResult);

                    }else {
                        $cloro= EnsayoCloro::select('resultado', 'consecutivo')->where('lc_ensayo_cloro.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($cloro[0]['resultado'])  ? $cloro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue ,$nitritoResult);
                    }
                //fin consulta cloro.

                //genera las 2 consultas para sulfato
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $sulfato= EnsayoSulfatos::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($sulfato[0]['sulfato_resultado_a'])  ? $sulfato[0]['sulfato_resultado_a']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue ,$nitritoResult);

                    }else {
                        $sulfato= EnsayoSulfatos::select('sulfato_resultado_a', 'consecutivo')->where('lc_ensayo_sulfatos.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($sulfato[0]['sulfato_resultado_a'])  ? $sulfato[0]['sulfato_resultado_a']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue ,$nitritoResult);
                    }
                //fin consulta sulfato.

                //genera las 2 consultas para hierro
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $hierro= EnsayoHierro::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($hierro[0]['resultado'])  ? $hierro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('T'.$cellValue ,$nitritoResult);

                    }else {
                        $hierro= EnsayoHierro::select('resultado', 'consecutivo')->where('lc_ensayo_hierro.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($hierro[0]['resultado'])  ? $hierro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('T'.$cellValue ,$nitritoResult);
                    }
                //fin consulta hierro.

                //genera las 2 consultas para nitritos
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $nitritos= EnsayoNitritos::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($nitritos[0]['resultado'])  ? $nitritos[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('U'.$cellValue ,$nitritoResult);
                    

                    }else {
                        $nitritos= EnsayoNitritos::select('resultado', 'consecutivo')->where('lc_ensayo_nitritos.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($nitritos[0]['resultado'])  ? $nitritos[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('U'.$cellValue ,$nitritoResult);
                    }
                //fin consulta nitritos.

                //genera las 2 consultas para fosfato
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $fosfato= EnsayoFosfato::where('consecutivo',$value)->where('lc_ensayo_fosfato.created_at','like','%'.$fecha.'%')->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitratoResult = isset($fosfato[0]['resultado'])  ? $fosfato[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('V'.$cellValue ,$nitratoResult);

                    }else {
                        $fosfato= EnsayoFosfato::select('resultado', 'consecutivo')->where('lc_ensayo_fosfato.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitratoResult = isset($fosfato[0]['resultado'])  ? $fosfato[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('V'.$cellValue ,$nitratoResult);
                    }
                //fin consulta fosfato


                //genera las 2 consultas para nitratos
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $nitratos= EnsayoNitratos::where('consecutivo',$value)->where('lc_ensayo_nitratos.created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitratoResult = isset($nitratos[0]['resultado'])  ? $nitratos[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('W'.$cellValue ,$nitratoResult);

                    }else {
                        $nitratos= EnsayoNitratos::select('resultado', 'consecutivo')->where('lc_ensayo_nitratos.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitratoResult = isset($nitratos[0]['resultado'])  ? $nitratos[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('W'.$cellValue ,$nitratoResult);
                    }
                //fin consulta nitratos

                //genera las 2 consultas para COT
                 if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                    $turbidez= EnsayoCarbonoOrganico::where('consecutivo',$value)->where('lc_ensayo_carbono_organico.created_at','like','%'.$fecha.'%')->get()->toArray();
                    $turbidezResult = isset($turbidez[0]['resultado'])  ? $turbidez[0]['resultado']: "NA";
                    $spreadsheet->getActiveSheet()->setCellValue('X'.$cellValue ,$turbidezResult);
                    
                    }else {
                        $turbidez= EnsayoCarbonoOrganico::select('resultado', 'consecutivo')->where('consecutivo', $value)->where('lc_ensayo_carbono_organico.created_at','like','%'.$fecha.'%')
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $turbidezResult = isset($turbidez[0]['resultado'])  ? $turbidez[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('X'.$cellValue ,$turbidezResult);
                    }
                //fin consulta COT

                $cellValue++;
                $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);
            }
            //  $spreadsheet->sort('B16', 'ASC');


            //Configuraciones de los encabezados del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="VIG-LECA-R-014.xlsx"');
            header('Cache-Control: max-age=0');
    
            // Exportacion del archivo
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
            $writer->save('php://output');
            exit;
    
            return $this->sendResponse($writer, trans('msg_success_update'));
        } else if ($tipo_Reporte == '33') {
            $quantity=ReportManagement::count();
            $fileType = 'Xlsx';
            $storagePath = storage_path('public/leca/report_management/VIG-LECA-R-033.xlsx');
            $cellValue=16;// desde la fila donde se empezara a renderizar.

        
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
            $spreadsheet = $reader->load($storagePath);
            // Invoca la función que genera la página 2 del reporte 033
            self::getReporte033Pag2($spreadsheet, $fecha);
            $spreadsheet->setActiveSheetIndex(0);

            if ($user->url_digital_signature) {
                //tratado para agregar la firma del usuario en sesión.              
               $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
               $drawing->setPath(storage_path( 'app/public/'.$user->url_digital_signature)); /* put your path and image here */
               $drawing->setCoordinates('L36');
               $drawing->setWorksheet($spreadsheet->getActiveSheet());
               $drawing->setHeight(36);
               $drawing->setResizeProportional(true);
               $drawing->setOffsetX(2); // this is how
               $drawing->setOffsetY(2);
           }
            

            if($quantity != 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore('50', $quantity - 1);
            }
            $spreadsheet->getActiveSheet()->setCellValue('U8', date("y", $fecha_reporte));
            $spreadsheet->getActiveSheet()->setCellValue('V8', date("m", $fecha_reporte));
            $spreadsheet->getActiveSheet()->setCellValue('W8', date("d", $fecha_reporte));

            $spreadsheet->getActiveSheet()->setCellValue('J24',$user->name);
            $spreadsheet->getActiveSheet()->setCellValue('C24',implode(" - ", $analistas));


            
            $bk = 0;
            $std = 0;
            $cellValue=16;
           
            foreach ($array as  $value) {


                if (str_contains($value, 'STD')) {
                    if ($std == 0) {
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,'STD'); //genera las columnas con los consecutivos.
                        $std ++;
                    }else {
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,'STD-'.$std); //genera las columnas con los consecutivos.
                        $std ++;
                    }
                    
                }
                else if  (str_contains($value, 'BK')) {
                    if ($bk == 0) {
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,'BK'); //genera las columnas con los consecutivos.
                        $bk ++;
                    }else {
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,'BK-'.$bk); //genera las columnas con los consecutivos.
                        $bk ++;
                    }
                }
                else {
                    $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,$value); //genera las columnas con los consecutivos.
                }


                
                // $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue ,$value); //genera las columnas con los consecutivos.

                 //genera las 2 consultas para turbidez
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $turbidez= EnsayoTurbidez::where('consecutivo',$value)->where('lc_ensayo_turbidez.created_at','like','%'.$fecha.'%')->get()->toArray();
                        $turbidezResult = isset($turbidez[0]['expresion'])  ? $turbidez[0]['expresion']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue ,$turbidezResult);
                        
                    }else {
                        $turbidez= EnsayoTurbidez::select('expresion', 'consecutivo')->where('consecutivo', $value)->where('lc_ensayo_turbidez.created_at','like','%'.$fecha.'%')
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $turbidezResult = isset($turbidez[0]['expresion'])  ? $turbidez[0]['expresion']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue ,$turbidezResult);
                    }
                //fin consulta turbidez

                //genera las 2 consultas para color
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $color= EnsayoColor::where('consecutivo',$value)->where('lc_ensayo_color.created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($color[0]['color'])  ? $color[0]['color']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue ,$nitritoResult);

                    }else {
                        $color= EnsayoColor::select('color', 'consecutivo')->where('lc_ensayo_color.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($color[0]['color'])  ? $color[0]['color']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue ,$nitritoResult);
                    }
                //fin consulta color.

                //genera las 2 consultas para ph
                 if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $ph= EnsayoPh::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        // array_push($analistas,$ph[0]['user_name']);
                        $phResult = isset($ph[0]['resultado'])  ? $ph[0]['resultado']: "NA";
                        $phResultT = isset($ph[0]['temperatura_ph'])  ? $ph[0]['temperatura_ph']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue ,$phResult);
                        $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue ,$phResultT);


                    }else {
                        $ph= EnsayoPh::select('resultado', 'consecutivo','temperatura_ph')->where('consecutivo', $value)->where('lc_ensayo_ph.created_at','like','%'.$fecha.'%')
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $phResultT = isset($ph[0]['temperatura_ph'])  ? $ph[0]['temperatura_ph']: "NA";
                        $phResult = isset($ph[0]['resultado'])  ? $ph[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue ,$phResult);
                        $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue ,$phResultT);
                    }
                //fin consulta ph

                  //genera las 2 consultas para conductividad
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $conductividad= EnsayoConductividad::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $conductividadResult = isset($conductividad[0]['conductividad'])  ? $conductividad[0]['conductividad']: "NA";
                        $conductividadResultT = isset($conductividad[0]['temperatura_con'])  ? $conductividad[0]['temperatura_con']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue ,$conductividadResult);
                        $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue ,$conductividadResultT);

                    }else {
                        $conductividad= EnsayoConductividad::select('conductividad', 'consecutivo','temperatura_con')->where('lc_ensayo_conductividad.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $conductividadResultT = isset($conductividad[0]['temperatura_con'])  ? $conductividad[0]['temperatura_con']: "NA";
                        $conductividadResult = isset($conductividad[0]['conductividad'])  ? $conductividad[0]['conductividad']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue ,$conductividadResult);
                        $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue ,$conductividadResultT);
                    }
                //fin consulta conductividad.

                 //genera las 2 consultas para olor sustanciasFlotantes
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $olor= EnsayoOlor::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($olor[0]['olor'])  ? $olor[0]['olor']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue ,$nitritoResult);

                    }else {
                        $olor= EnsayoOlor::select('olor', 'consecutivo')->where('lc_ensayo_olor.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($olor[0]['olor'])  ? $olor[0]['olor']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue ,$nitritoResult);
                    }
                //fin consulta olor.

                //genera las 2 consultas para sustanciasFlotantes sustanciasFlotantes
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $sustanciasFlotantes= EnsayosustanciasFlotantes::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($sustanciasFlotantes[0]['sustanciasFlotantes'])  ? $sustanciasFlotantes[0]['sustanciasFlotantes']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue ,$nitritoResult);

                    }else {
                        $sustanciasFlotantes= EnsayosustanciasFlotantes::select('sustanciasFlotantes', 'consecutivo')->where('lc_ensayo_sustancias_flotantes.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($sustanciasFlotantes[0]['sustanciasFlotantes'])  ? $sustanciasFlotantes[0]['sustanciasFlotantes']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue ,$nitritoResult);
                    }
                //fin consulta sustanciasFlotantes.
                
                //genera las 2 consultas para acidez
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $acidez= EnsayoAcidez::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($acidez[0]['resultado'])  ? $acidez[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue ,$nitritoResult);

                    }else {
                        $acidez= Ensayoacidez::select('resultado', 'consecutivo')->where('lc_ensayo_acidez.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($acidez[0]['resultado'])  ? $acidez[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue ,$nitritoResult);
                    }
                //fin consulta acidez.

                //genera las 2 consultas para cloruro
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $cloruro= EnsayoCloruro::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($cloruro[0]['resultado'])  ? $cloruro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue ,$nitritoResult);

                    }else {
                        $cloruro= EnsayoCloruro::select('resultado', 'consecutivo')->where('lc_ensayo_cloruro.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($cloruro[0]['resultado'])  ? $cloruro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue ,$nitritoResult);
                    }
                //fin consulta cloruro.

                //genera las 2 consultas para dureza
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $dureza= EnsayoDureza::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($dureza[0]['resultado'])  ? $dureza[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue ,$nitritoResult);

                    }else {
                        $dureza= EnsayoDureza::select('resultado', 'consecutivo')->where('lc_ensayo_dureza.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($dureza[0]['resultado'])  ? $dureza[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue ,$nitritoResult);
                    }
                //fin consulta dureza.

                //genera las 2 consultas para alcalinidad
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $alcalinidad= EnsayoAlcalinidad::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $alcalinidadResult = isset($alcalinidad[0]['resultado'])  ? $alcalinidad[0]['resultado']: "NA";
                        $alcalinidadResultT = isset($alcalinidad[0]['ph'])  ? $alcalinidad[0]['ph']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue ,$alcalinidadResult);
                        // $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue ,$alcalinidadResultT);

                    }else {
                        $alcalinidad= EnsayoAlcalinidad::select('resultado', 'consecutivo','ph')->where('lc_ensayo_alcalinidad.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $alcalinidadResultT = isset($alcalinidad[0]['ph'])  ? $alcalinidad[0]['ph']: "NA";
                        $alcalinidadResult = isset($alcalinidad[0]['resultado'])  ? $alcalinidad[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue ,$alcalinidadResult);
                        // $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue ,$alcalinidadResultT);
                    }
                //fin consulta alcalinidad.
                
                //genera las 2 consultas para calcio
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $calcio= EnsayoCalcio::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($calcio[0]['resultado'])  ? $calcio[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue ,$nitritoResult);

                    }else {
                        $calcio= EnsayoCalcio::select('resultado', 'consecutivo')->where('lc_ensayo_calcio.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($calcio[0]['resultado'])  ? $calcio[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue ,$nitritoResult);
                    }
                //fin consulta calcio.

                //genera las 2 consultas para sulfato
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $sulfato= EnsayoSulfatos::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($sulfato[0]['sulfato_resultado_a'])  ? $sulfato[0]['sulfato_resultado_a']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue ,$nitritoResult);

                    }else {
                        $sulfato= EnsayoSulfatos::select('sulfato_resultado_a', 'consecutivo')->where('lc_ensayo_sulfatos.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($sulfato[0]['sulfato_resultado_a'])  ? $sulfato[0]['sulfato_resultado_a']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue ,$nitritoResult);
                    }
                //fin consulta sulfato.

                //genera las 2 consultas para aluminio
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $aluminio= EnsayoAluminio::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($aluminio[0]['resultado'])  ? $aluminio[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue ,$nitritoResult);

                    }else {
                        $aluminio= EnsayoAluminio::select('resultado', 'consecutivo')->where('lc_ensayo_aluminio.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($aluminio[0]['resultado'])  ? $aluminio[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue ,$nitritoResult);
                    }
                //fin consulta aluminio.

                //genera las 2 consultas para hierro
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $hierro= EnsayoHierro::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($hierro[0]['resultado'])  ? $hierro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue ,$nitritoResult);

                    }else {
                        $hierro= EnsayoHierro::select('resultado', 'consecutivo')->where('lc_ensayo_hierro.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($hierro[0]['resultado'])  ? $hierro[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue ,$nitritoResult);
                    }
                //fin consulta hierro.

                //genera las 2 consultas para nitritos
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $nitritos= EnsayoNitritos::where('consecutivo',$value)->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitritoResult = isset($nitritos[0]['resultado'])  ? $nitritos[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue ,$nitritoResult);
                    

                    }else {
                        $nitritos= EnsayoNitritos::select('resultado', 'consecutivo')->where('lc_ensayo_nitritos.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitritoResult = isset($nitritos[0]['resultado'])  ? $nitritos[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue ,$nitritoResult);
                    }
                //fin consulta nitritos.

                //genera las 2 consultas para fosfato
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $fosfato= EnsayoFosfato::where('consecutivo',$value)->where('lc_ensayo_fosfato.created_at','like','%'.$fecha.'%')->where('created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitratoResult = isset($fosfato[0]['resultado'])  ? $fosfato[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('T'.$cellValue ,$nitratoResult);

                    }else {
                        $fosfato= EnsayoFosfato::select('resultado', 'consecutivo')->where('lc_ensayo_fosfato.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitratoResult = isset($fosfato[0]['resultado'])  ? $fosfato[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('T'.$cellValue ,$nitratoResult);
                    }
                //fin consulta fosfato


                //genera las 2 consultas para nitratos
                    if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                        $nitratos= EnsayoNitratos::where('consecutivo',$value)->where('lc_ensayo_nitratos.created_at','like','%'.$fecha.'%')->get()->toArray();
                        $nitratoResult = isset($nitratos[0]['resultado'])  ? $nitratos[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('U'.$cellValue ,$nitratoResult);

                    }else {
                        $nitratos= EnsayoNitratos::select('resultado', 'consecutivo')->where('lc_ensayo_nitratos.created_at','like','%'.$fecha.'%')->where('consecutivo', $value)
                        ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                        ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                        ->where( 'toma.type_water','<>','Cruda' )
                        ->get()->toArray();

                        $nitratoResult = isset($nitratos[0]['resultado'])  ? $nitratos[0]['resultado']: "NA";
                        $spreadsheet->getActiveSheet()->setCellValue('U'.$cellValue ,$nitratoResult);
                    }
                //fin consulta nitratos

                //genera las 2 consultas para COT
                    // if (str_contains($value, 'BK') || str_contains($value, 'STD') ) {
                    // $turbidez= EnsayoCarbonoOrganico::where('consecutivo',$value)->where('lc_ensayo_carbono_organico.created_at','like','%'.$fecha.'%')->get()->toArray();
                    // $turbidezResult = isset($turbidez[0]['resultado'])  ? $turbidez[0]['resultado']: "NA";
                    // $spreadsheet->getActiveSheet()->setCellValue('V'.$cellValue ,$turbidezResult);
                    
                    // }else {
                    //     $turbidez= EnsayoCarbonoOrganico::select('resultado', 'consecutivo')->where('consecutivo', $value)->where('lc_ensayo_carbono_organico.created_at','like','%'.$fecha.'%')
                    //     ->join('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                    //     ->join('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                    //     ->where( 'toma.type_water','<>','Cruda' )
                    //     ->get()->toArray();

                    //     $turbidezResult = isset($turbidez[0]['resultado'])  ? $turbidez[0]['resultado']: "NA";
                    //     $spreadsheet->getActiveSheet()->setCellValue('V'.$cellValue ,$turbidezResult);
                    // }
                //fin consulta COT

                $cellValue++;
                $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);
            }

            //Configuraciones de los encabezados del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="VIG-LECA-R-033.xlsx"');
            header('Cache-Control: max-age=0');
    
            // Exportacion del archivo
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
            $writer->save('php://output');
            exit;
    
            return $this->sendResponse($writer, trans('msg_success_update'));
        } else{
                return $this->sendSuccess('No se encuentran registros con esos filtros desde controlador.', 'error');
                
        }

        
        
    }

    /**
     * Llena el reporte 014 de la página 2 de los ensayos de microbiología del tipo de agua tratada
     * 
     * @param mixed $spreadsheet plantilla del reporte de excel
     * @param mixed $fecha fecha en la que se va a generar el reporte
     */
    public function getReporte014Pag2($spreadsheet, $fecha) {
        // Selecciona la hoja 2 del reporte
        $spreadsheet->setActiveSheetIndex(1);
        // Formato de fecha (AA-MM-DD)
        $fecha_reporte = strtotime(date($fecha));
        // Escribe la fecha (AA-MM-DD)
        $spreadsheet->getActiveSheet()->setCellValue('H8', date("y", $fecha_reporte));
        $spreadsheet->getActiveSheet()->setCellValue('I8', date("m", $fecha_reporte));
        $spreadsheet->getActiveSheet()->setCellValue('J8', date("d", $fecha_reporte));
        // Consulta los ensayos de microbiología, los analistas que realizaron los ensayos, donde el tipo de agua es diferente de cruda
        $ensayosMicro = EnsayoMicro::select('consecutivo', DB::raw("CONCAT('{', GROUP_CONCAT(CONCAT('\"', ensayo, '\" : ',resultado)), '}') as valor"), 'tipo', 'toma.type_water', 'lc_ensayos_microbiologicos.user_name')
                                ->leftJoin('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                                ->leftJoin('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                                ->where('lc_ensayos_microbiologicos.created_at', 'LIKE', '%'.$fecha.'%')
                                ->havingRaw('IF(tipo != "Blanco", toma.type_water <> "Cruda", 1)')
                                ->groupBy('consecutivo')
                                ->orderBy('lc_ensayos_microbiologicos.created_at')
                                ->get();
        // Obtiene los analistas que realizaron los ensayos, eliminando los repetidos
        $analistas = array_unique($ensayosMicro->pluck("user_name")->toArray());
        // Imprime los analistas en el reporte
        $spreadsheet->getActiveSheet()->setCellValue('C22', implode(" - ", $analistas));
        $spreadsheet->getActiveSheet()->setCellValue('C23', "Analistas microbiología");
        // Consulta el nombre y cargo de la persona en sesión
        $usuario = DB::table("users AS u")
                    ->select("u.name AS nombre", "c.nombre AS cargo")
                    ->leftJoin("cargos AS c", "u.id_cargo", "=", "c.id")
                    ->where("u.id", Auth::user()->id)
                    ->get()->first();
        // Imprime los analistas en el reporte
        $spreadsheet->getActiveSheet()->setCellValue('E22', $usuario->nombre);
        $spreadsheet->getActiveSheet()->setCellValue('E23', $usuario->cargo);
        // Fila donde inicia el registro de los ensayos en el reporte
        $cellValue = 15;
        foreach($ensayosMicro as $ensayo) {
            // Obtiene los valores de los ensayos
            $valor = json_decode($ensayo["valor"], true);
            // Valida si los diferentes tipos de ensayos existen en los datos obtenidos
            $existeColiformes = strpos($ensayo["valor"], "Coliformes");
            $existeEscherichia = strpos($ensayo["valor"], "Escherichia");
            $existeHeterotroficas = strpos($ensayo["valor"], "Heterotroficas");
            // Escribe el consecutivo
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $ensayo['consecutivo']);
            // Escribe el valor del ensayo, dependiendo del tipo de ensayo
            $existeHeterotroficas !== false ? $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue , $valor['Heterotroficas'] == 0 ? '<2' : ($valor['Heterotroficas'] > 1000 ? '>1000' : $valor['Heterotroficas'])) : $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, 'NA');
            $existeColiformes !== false ? $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue , $valor['Coliformes'] == 0 ? '<1' : ($valor['Coliformes'] > 1000 && $ensayo['tipo'] != 'Duplicado' ? '>1000' : ($valor['Coliformes'] > 241960 && $ensayo['tipo'] == 'Duplicado' ? '>241960': $valor['Coliformes']))) : $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, 'NA');
            $existeEscherichia !== false ? $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue , $valor['Escherichia'] == 0 ? '<1' : ($valor['Escherichia'] > 1000 && $ensayo['tipo'] != 'Duplicado' ? '>1000' : ($valor['Escherichia'] > 241960 && $ensayo['tipo'] == 'Duplicado' ? '>241960' : $valor['Coliformes']))) : $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, 'NA');
            // Imcrementa el valor de la fila
            $cellValue++;
            // Inserta una nueva fila en el reporte de excel
            $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);
            // Combina las celdas de acuerdo al formato de la fila
            $spreadsheet->getActiveSheet()->mergeCells('B'.$cellValue.':C'.$cellValue);
            $spreadsheet->getActiveSheet()->mergeCells('F'.$cellValue.':J'.$cellValue);
        }
    }

    /**
     * Llena el reporte 033 de la página 2 de los ensayos de microbiología del tipo de agua cruda
     * 
     * @param mixed $spreadsheet plantilla del reporte de excel
     * @param mixed $fecha fecha en la que se va a generar el reporte
     */
    public function getReporte033Pag2($spreadsheet, $fecha) {
        // Selecciona la hoja 2 del reporte
        $spreadsheet->setActiveSheetIndex(1);
        // Formato de fecha (AA-MM-DD)
        $fecha_reporte = strtotime(date($fecha));
        // Escribe la fecha (AA-MM-DD)
        $spreadsheet->getActiveSheet()->setCellValue('E8', date("y", $fecha_reporte));
        $spreadsheet->getActiveSheet()->setCellValue('F8', date("m", $fecha_reporte));
        // Consulta los ensayos de microbiología, los analistas que realizaron los ensayos, donde el tipo de agua es diferente de cruda
        $ensayosMicro = EnsayoMicro::select('consecutivo', DB::raw("CONCAT('{', GROUP_CONCAT(CONCAT('\"', ensayo, '\" : ',resultado)), '}') as valor"), 'tipo', 'toma.type_water', 'lc_ensayos_microbiologicos.user_name')
                                ->leftJoin('lc_sample_taking_has_lc_list_trials as lista', 'lista.id', '=', 'lc_sample_taking_has_lc_list_trials_id')
                                ->leftJoin('lc_sample_taking as toma','toma.id', '=', 'lista.lc_sample_taking_id')
                                ->where('lc_ensayos_microbiologicos.created_at', 'LIKE', '%'.$fecha.'%')
                                ->havingRaw('IF(tipo != "Blanco", toma.type_water = "Cruda", 1)')
                                ->groupBy('consecutivo')
                                ->orderBy('lc_ensayos_microbiologicos.created_at')
                                ->get();
        // Obtiene los analistas que realizaron los ensayos, eliminando los repetidos
        $analistas = array_unique($ensayosMicro->pluck("user_name")->toArray());
        // Imprime el nombre y cargo del usuario que esta generando el reporte
        $spreadsheet->getActiveSheet()->setCellValue('C24', implode(" - ", $analistas));
        $spreadsheet->getActiveSheet()->setCellValue('C25', "Analistas microbiología");
        // Consulta el nombre y cargo de la persona en sesión
        $usuario = DB::table("users AS u")
                    ->select("u.name AS nombre", "c.nombre AS cargo")
                    ->leftJoin("cargos AS c", "u.id_cargo", "=", "c.id")
                    ->where("u.id", Auth::user()->id)
                    ->get()->first();
        // Imprime los analistas en el reporte
        $spreadsheet->getActiveSheet()->setCellValue('E24', $usuario->nombre);
        $spreadsheet->getActiveSheet()->setCellValue('E25', $usuario->cargo);
        // Fila donde inicia el registro de los ensayos en el reporte
        $cellValue = 16;
        foreach($ensayosMicro as $ensayo) {
            // Obtiene los valores de los ensayos
            $valor = json_decode($ensayo["valor"], true);
            // Valida si los diferentes tipos de ensayos existen en los datos obtenidos
            $existeColiformes = strpos($ensayo["valor"], "Coliformes");
            $existeEscherichia = strpos($ensayo["valor"], "Escherichia");
            $existeHeterotroficas = strpos($ensayo["valor"], "Heterotroficas");
            // Imprime el dia de la ejecución del ensayo
            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, date('d', $ensayo['created_at']));
            // Escribe el consecutivo
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $ensayo['consecutivo']);
            // Escribe el valor del ensayo, dependiendo del tipo de ensayo
            $existeHeterotroficas !== false ? $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue ,$valor['Heterotroficas']) : $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue, 'NA');
            $existeColiformes !== false ? $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue ,$valor['Coliformes']) : $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, 'NA');
            $existeEscherichia !== false ? $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue ,$valor['Escherichia']) : $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue, 'NA');
            // Imcrementa el valor de la fila
            $cellValue++;
            // Inserta una nueva fila en el reporte de excel
            $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);
            // Combina las celdas de acuerdo al formato de la fila
            $spreadsheet->getActiveSheet()->mergeCells('C'.$cellValue.':D'.$cellValue);
            $spreadsheet->getActiveSheet()->mergeCells('E'.$cellValue.':G'.$cellValue);
            $spreadsheet->getActiveSheet()->mergeCells('H'.$cellValue.':I'.$cellValue);
        }
       
        
    }

}
