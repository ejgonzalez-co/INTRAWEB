<?php

namespace Modules\CitizenPoll\Http\Controllers;

use App\Exports\GenericExport;
use Modules\CitizenPoll\Http\Requests\CreatePollsRequest;
use Modules\CitizenPoll\Http\Requests\UpdatePollsRequest;
use Modules\CitizenPoll\Repositories\PollsRepository;
use App\Http\Controllers\AppBaseController;
use Modules\CitizenPoll\Models\ImageManager;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Exports\CitizenPoll\RequestExport;

use Modules\UpdateCitizenData\Models\Polls;


/**
 * Descripcion de la clase
 *
 * @author Andres Stiven Pinzon G. - Mayo. 19 - 2021
 * @version 1.0.0
 */
class CitizenPollsController extends AppBaseController {

    /** @var  PollsRepository */
    private $pollsRepository;

    /**
     * Constructor de la clase
     *
     * @author Andres Stiven Pinzon G. - Mayo. 19 - 2021
     * @version 1.0.0
     */
    public function __construct(PollsRepository $pollsRepo) {
        $this->pollsRepository = $pollsRepo;
     
    }

    
    /**
     * Muestra la vista para el CRUD de UdcRequest.
     *
     * @author Andres Stiven Pinzon G. - Mayo. 19 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('citizen_poll::polls.index_citizen');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Andres Stiven Pinzon G. - Mayo. 19 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {

        $polls = $this->pollsRepository->all();
        return $this->sendResponse($polls->toArray(), trans('data_obtained_successfully'));

    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Mayo. 19 - 2021
     * @version 1.0.0
     *
     * @param CreateUdcRequestRequest $request
     *
     * @return Response
     */
    public function store(CreatePollsRequest $request) {

        $input = $request->all();
        //dd($input);

        $polls = $this->pollsRepository->create($input);
        
        return $this->sendResponse($polls->toArray(), trans('Encuesta enviada satisfactoriamente!, ¡Gracias por ayudarnos a mejorar!, Tu opinión nos importa'), "success");
        }


    /**
     * Envia datos al banner
     *
     * @author José Manuel Marín Londoño. - Dic. 16 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getSlider(){
        $image_managers = ImageManager::select("url_image")->get()->toArray();
        $newArray = [];
        $count = 0;
        // $array=array(
        //     array('url'=>"../storage/citizen_poll/config/url_image/UWOEGzPijFSEYr9GDm5OAmHClBIXT4zMHAPKJ5WI.png")
        // );

        //Crea un foreach que reccorre la consulta
        foreach($image_managers as $imafeFile){
            $newArray[$count] = array('url'=>"../storage/".$imafeFile['url_image']);
            $count ++;
        }
        return $this->sendResponse($newArray, trans('msg_success_save'));
    }

}

?>