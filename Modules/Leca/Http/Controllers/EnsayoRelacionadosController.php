<?php

namespace Modules\Leca\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\MonthlyRoutinesOfficials;
use Modules\Leca\Models\SampleTaking;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Response;

class EnsayoRelacionadosController extends AppBaseController
{

    /**
     * Muestra la vista para el CRUD de SampleTaking.
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 07 - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index()
    {

        return view('leca::EnsayoRelacionado.index');

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

        $user = Auth::user();
        $arrayTotal = [];
        $arraySegundo = [];

        if (!Auth::user()->hasRole('Administrador Leca')) {
            $ensayos = MonthlyRoutinesOfficials::with('lcListTrials', 'lcMonthlyRoutines')->whereDate('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))->whereDate('fecha_inicio', '<=', Carbon::now()->format('Y-m-d'))->where('users_id', $user->id)->get();

            $list_trials = ListTrials::with(['lcNitritos', 'lcNitratos', 'lcHierro', 'lcFosfatos', 'lcAluminio', 'lcCloruro', 'lcCloroResidual', 'lcCalcio', 'lcDurezaTotal', 'lcAcidez', 'lcFluoruros', 'lcSulfatos', 'lcAlcalinidad', 'lcPh', 'lcBlancos', 'lcPatron', 'lcColiformesTotales', 'lcEscherichiaColi', 'lcBacteriasHeterotroficas', 'lcColor', 'lcOlor', 'lcConductividad', 'lcSustanciasFlotantes', 'lcCarbonoOrganico', 'lcSolidos', 'lcPlomo', 'lcCadmio', 'lcMercurio', 'lcHidrocarburos', 'lcPlaguicidas', 'lcTrialometanos', 'lcOlor', 'lcConductividad', 'lcSustanciasFlotantes', 'lcTurbidez'])->latest()->get();

            foreach ($ensayos as $key => $value) {
                foreach ($value->lcListTrials as $key => $item) {
                    array_push($arrayTotal, $item->id);
                }
            }

            foreach ($list_trials as $key => $value) {
                $var = array_search($value->id, $arrayTotal);
                foreach ($arrayTotal as $key => $item) {
                    if ($item == $value->id) {
                        array_push($arraySegundo, $value);
                    }
                }
            }
        } else {

            $arraySegundo = ListTrials::with(['lcNitritos', 'lcNitratos', 'lcHierro', 'lcFosfatos', 'lcAluminio', 'lcCloruro', 'lcCloroResidual', 'lcCalcio', 'lcDurezaTotal', 'lcAcidez', 'lcFluoruros', 'lcSulfatos', 'lcAlcalinidad', 'lcPh', 'lcBlancos', 'lcPatron', 'lcColiformesTotales', 'lcEscherichiaColi', 'lcBacteriasHeterotroficas', 'lcColor', 'lcOlor', 'lcConductividad', 'lcSustanciasFlotantes', 'lcCarbonoOrganico', 'lcSolidos', 'lcPlomo', 'lcCadmio', 'lcMercurio', 'lcHidrocarburos', 'lcPlaguicidas', 'lcTrialometanos', 'lcOlor', 'lcConductividad', 'lcSustanciasFlotantes', 'lcTurbidez'])->latest()->get();

        }

        return $this->sendResponse($arraySegundo, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function show()
    {

        return $this->sendResponse($arraySegundo, trans('data_obtained_successfully'));
    }

}