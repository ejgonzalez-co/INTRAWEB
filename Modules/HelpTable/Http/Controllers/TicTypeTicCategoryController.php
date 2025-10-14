<?php

namespace Modules\HelpTable\Http\Controllers;
use Modules\HelpTable\Models\TicTypeAsset;

use App\Exports\GenericExport;
use Modules\HelpTable\Models\TicTypeTicCategoryHistory;
use Modules\HelpTable\Http\Requests\CreateTicTypeTicCategoryRequest;
use Modules\HelpTable\Http\Requests\UpdateTicTypeTicCategoryRequest;
use Modules\HelpTable\Repositories\TicTypeTicCategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Modules\HelpTable\Models\TicTypeTicCategory;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicTypeTicCategoryController extends AppBaseController {

    /** @var  TicTypeTicCategoryRepository */
    private $ticTypeTicCategoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TicTypeTicCategoryRepository $ticTypeTicCategoryRepo) {
        $this->ticTypeTicCategoryRepository = $ticTypeTicCategoryRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicTypeTicCategory.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador TIC"])){
            return view('help_table::tic_type_tic_categories.index');
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
            $tic_type_tic_categories = TicTypeTicCategory::with('ticTypeCategoryHistory','ticTypeAssets')->get();
            return $this->sendResponse($tic_type_tic_categories->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicTypeTicCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateTicTypeTicCategoryRequest $request)
    {
        // Obtener todos los datos de la solicitud
        $input = $request->all();
    
        try {
            // Crear la categoría en la base de datos
            $ticTypeTicCategory = $this->ticTypeTicCategoryRepository->create($input);
    
            if (!$ticTypeTicCategory) {
                return $this->sendError(trans('error_creating_category'), 500);
            }
    
            $typesHistory = [];
            if (!empty($input['tic_type_assets'])) {
                    // Decodificar el JSON de las categorías y extraer los nombres
                $types = array_map(function ($item) {
                    return json_decode($item, true)['name'] ?? null;
                }, $input['tic_type_assets']);
        
                // Insertar nuevas categorías y almacenar sus nombres en $typesHistory
                foreach ($types as $type) {
                    $types = [
                        'name' => $type,
                        'ht_tic_type_tic_categories_id' => $ticTypeTicCategory->id
                    ];
                    $typesHistory[] = $type;
                    TicTypeAsset::create($types);
                }

                   
    
                // Convertir el historial de tipos a una cadena separada por comas
                $typesHistory = implode(',', $typesHistory);

                // Guardar el registro en la tabla de historial
                $ticTypeTicCategoryHistory = new TicTypeTicCategoryHistory();
                $ticTypeTicCategoryHistory->name = $ticTypeTicCategory->name;
                $ticTypeTicCategoryHistory->estado = $ticTypeTicCategory->estado;
                $ticTypeTicCategoryHistory->id_usuario = Auth::user()->id;
                $ticTypeTicCategoryHistory->id_categories = $ticTypeTicCategory->id;
                $ticTypeTicCategoryHistory->name_user = Auth::user()->fullName;
                $ticTypeTicCategoryHistory->tipos = $typesHistory;
                $ticTypeTicCategoryHistory->save();
            }
    
         
    
            // Cargar relaciones antes de retornar la respuesta
            $ticTypeTicCategory->load('ticTypeCategoryHistory', 'ticTypeAssets');
    
            return $this->sendResponse($ticTypeTicCategory->toArray(), trans('msg_success_save'));
    
        } catch (\Illuminate\Database\QueryException $error) {
            // Registrar el error en los logs
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicTypeTicCategoryController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
    
            // Retornar mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
    
        } catch (\Exception $e) {
            // Registrar el error en los logs
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicTypeTicCategoryController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage(). "Linea: " . $e->getLine());
    
            // Retornar error de tipo lógico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }
    

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTicTypeTicCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicTypeTicCategoryRequest $request) 
{
    // Obtener todos los datos de la solicitud
    $input = $request->all();
    /** @var TicTypeTicCategory $ticTypeTicCategory */
    // Buscar la categoría por ID
    $ticTypeTicCategory = $this->ticTypeTicCategoryRepository->find($id);

    // Si la categoría no existe, retorna un error
    if (empty($ticTypeTicCategory)) {
        return $this->sendError(trans('not_found_element'), 200);
    }

    try {
        $typesHistory = [];

        // Decodificar el JSON de las categorías y extraer los nombres
        $types = array_map(function ($item) {
            return json_decode($item, true)['name'] ?? null;
        }, $input['tic_type_assets']);

        // Eliminar todas las categorías asociadas antes de crear nuevas
        TicTypeAsset::where('ht_tic_type_tic_categories_id', $ticTypeTicCategory->id)->delete();

        // Insertar nuevas categorías y almacenar sus nombres en $typesHistory
        foreach ($types as $type) {
            $types = [
                'name' => $type,
                'ht_tic_type_tic_categories_id' => $ticTypeTicCategory->id
            ];
            $typesHistory[] = $type;
            TicTypeAsset::create($types);
        }

        // Convertir el historial de tipos a una cadena separada por comas
        $typesHistory = implode(',', $typesHistory);

        // Actualizar la categoría en la base de datos
        $ticTypeTicCategory = $this->ticTypeTicCategoryRepository->update($input, $id);

        if ($ticTypeTicCategory) {
            // Guardar el registro en la tabla de historial
            $ticTypeTicCategoryHistory = new TicTypeTicCategoryHistory();
            $ticTypeTicCategoryHistory->name = $ticTypeTicCategory->name;
            $ticTypeTicCategoryHistory->estado = $ticTypeTicCategory->estado;
            $ticTypeTicCategoryHistory->id_usuario = Auth::user()->id;
            $ticTypeTicCategoryHistory->id_categories = $ticTypeTicCategory->id;
            $ticTypeTicCategoryHistory->name_user = Auth::user()->fullName;
            $ticTypeTicCategoryHistory->tipos = $typesHistory;
            $ticTypeTicCategoryHistory->save();
        }

        // Cargar relaciones antes de retornar la respuesta
        $ticTypeTicCategory->ticTypeCategoryHistory;
        $ticTypeTicCategory->ticTypeAssets;

        return $this->sendResponse($ticTypeTicCategory->toArray(), trans('msg_success_update'));

    } catch (\Illuminate\Database\QueryException $error) {
        // Registrar el error en los logs
        $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicTypeTicCategoryController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());

        // Retornar mensaje de error de base de datos
        return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');

    } catch (\Exception $e) {
        // Registrar el error en los logs
        $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicTypeTicCategoryController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage(). 'Linea: '.$e->getLine());

        // Retornar mensaje de error general
        return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
    }
}


    /**
     * Elimina un TicTypeTicCategory del almacenamiento
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

        /** @var TicTypeTicCategory $ticTypeTicCategory */
        $ticTypeTicCategory = $this->ticTypeTicCategoryRepository->find($id);

        if (empty($ticTypeTicCategory)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticTypeTicCategory->delete();

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
        $fileName = time().'-'.trans('tic_type_tic_categories').'.'.$fileType;

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

    public function getCategories() {
        $tic_type_tic_categories = TicTypeTicCategory::where('estado', 'Activo')->get();
        return $this->sendResponse($tic_type_tic_categories->toArray(), trans('data_obtained_successfully'));
    }
}
