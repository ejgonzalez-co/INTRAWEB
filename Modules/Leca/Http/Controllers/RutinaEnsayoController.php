<?php

namespace Modules\Leca\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\MonthlyRoutinesOfficials;
use Modules\Leca\Models\SampleTaking;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Response;

class RutinaEnsayoController extends AppBaseController
{

    /**
     * Muestra la vista para el CRUD de SampleTaking.
     *
     * @authoNicolas Dario Ortiz Peña. - Enero. 07 - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index()
    {

        $user = Auth::user();
        $arrayTotal = [];
        $arraySegundo = [];
        $arrayTercero = [];

        $ensayos = MonthlyRoutinesOfficials::with('lcListTrials', 'lcMonthlyRoutines')->where('users_id', $user->id)->whereDate('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))->whereDate('fecha_inicio', '<=', Carbon::now()->format('Y-m-d'))->get();

        $sample_takings = SampleTaking::with('lcListTrials')->where('estado_analisis', '!=', '')->get();

        foreach ($ensayos as $key => $value) {
            foreach ($value->lcListTrials as $key => $item) {
                array_push($arrayTotal, $item->id);
            }
        }
        
        foreach ($sample_takings as $key => $value) {
            foreach ($value->lcListTrials as $key => $item) {
                $var = array_search($item->id, $arrayTotal);

                if ($var) {

                    array_push($arraySegundo, $value);
                }
            }

        }

        return view('leca::rutina_ensayo.index', compact('ensayos'))->with('sample_takings', $arraySegundo);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all()
    {
        $arrayTotal = [];
        $arraySegundo = [];
        $user = Auth::user();

        if (!Auth::user()->hasRole('Administrador Leca')){
            $ensayos = MonthlyRoutinesOfficials::with('lcListTrials', 'lcMonthlyRoutines')->where('users_id', $user->id)->whereDate('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))->whereDate('fecha_inicio', '<=', Carbon::now()->format('Y-m-d'))->latest()->get();

            foreach ($ensayos as $key => $value) {
                foreach ($value->lcListTrials as $key => $item) {
                    array_push($arrayTotal, $item->id);
                }
            }
    
            $sample_takings = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('estado_analisis', '!=', '')->latest()->get();
    
            for ($i = 0; $i < count($sample_takings); $i++) {
                
                for ($x = 0; $x < count($sample_takings[$i]->lcListTrials); $x++) {
                    
                    $var = in_array($sample_takings[$i]->lcListTrials[$x]->id, $arrayTotal);
                    
                    if ($var==true) {
                        array_push($arraySegundo, $sample_takings[$i]);
                        $x=count($sample_takings[$i]->lcListTrials);
                    }
                }
            }
        }else{

            $arraySegundo = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('estado_analisis', '!=', '')->latest()->get();

        }



        return $this->sendResponse($arraySegundo, trans('data_obtained_successfully'));
    }
}
