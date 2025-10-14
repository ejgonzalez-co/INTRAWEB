<?php

namespace Modules\PQRS\Http\Controllers;

use App\Exports\GenericExport;
use Modules\PQRS\Http\Requests\CreateHolidayCalendarRequest;
use Modules\PQRS\Http\Requests\UpdateHolidayCalendarRequest;
use Modules\PQRS\Repositories\HolidayCalendarRepository;
use Modules\PQRS\Models\HolidayCalendar;
use Modules\PQRS\Models\WorkingHours;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Carbon\Carbon;
use Auth;
use DB;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class HolidayCalendarController extends AppBaseController {

    /** @var  HolidayCalendarRepository */
    private $holidayCalendarRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(HolidayCalendarRepository $holidayCalendarRepo) {
        $this->holidayCalendarRepository = $holidayCalendarRepo;
    }

    /**
     * Muestra la vista para el CRUD de HolidayCalendar.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole("Administrador de requerimientos")){
            return view('help_table:holiday_calendars.index');
        }
        return view("auth.forbidden");
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $holidayCalendars = $this->holidayCalendarRepository->all();

        $workingHours = WorkingHours::latest()->get();


        return $this->sendResponse($holidayCalendars->toArray(), trans('data_obtained_successfully'));
    }

    
    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function allWorkingHours() {
        $workingHours = WorkingHours::latest()
            ->get()
            ->map(function($item, $key){
                $holidayCalendars = $this->holidayCalendarRepository->all()->toArray();

                $item->holiday_calendars =  $holidayCalendars;
                return $item;
            });

        return $this->sendResponse($workingHours->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();


        
        // Valida si viene fechas para asignar
        if (!empty($input['date'])) {

            $holidayCalendar = HolidayCalendar::truncate();

            // Recorre las fechas seleccionadas
            foreach ($input['date'] as $key => $date) {
                
                $date = str_replace('"','', $date);

                $holidayCalendar = $this->holidayCalendarRepository->create([
                    'date' => Carbon::parse($date)->format('Y-m-d'),
                ]);
            }
        }

        $userAuth = Auth::user();

        // Crea el registro de version
        DB::table('pqr_holiday_calendar_version')->insert([
            'creator_id' => $userAuth->id,
            'creator_name' => $userAuth->fullname,
            'date_hour_information' => json_encode($input),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        WorkingHours::truncate();
        $workingHours = WorkingHours::create($input);

        return $this->sendResponse($holidayCalendar->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateHolidayCalendarRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHolidayCalendarRequest $request) {

        $input = $request->all();

        /** @var HolidayCalendar $holidayCalendar */
        $holidayCalendar = $this->holidayCalendarRepository->find($id);

        if (empty($holidayCalendar)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $holidayCalendar = $this->holidayCalendarRepository->update($input, $id);

        return $this->sendResponse($holidayCalendar->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un HolidayCalendar del almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var HolidayCalendar $holidayCalendar */
        $holidayCalendar = $this->holidayCalendarRepository->find($id);

        if (empty($holidayCalendar)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $holidayCalendar->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('holiday_calendars').'.'.$fileType;

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
}
