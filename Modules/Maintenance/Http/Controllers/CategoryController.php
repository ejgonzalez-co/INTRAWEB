<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateCategoryRequest;
use Modules\Maintenance\Http\Requests\UpdateCategoryRequest;
use Modules\Maintenance\Repositories\CategoryRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\Category;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

use Modules\Maintenance\Models\AuthorizedCategories;

use Modules\Maintenance\Models\ResumeEquipmentLeca;
use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use Modules\Maintenance\Models\ResumeEquipmentMachineryLeca;
use Modules\Maintenance\Models\ResumeInventoryLeca;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class CategoryController extends AppBaseController {

    /** @var  CategoryRepository */
    private $categoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(CategoryRepository $categoryRepo) {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Muestra la vista para el CRUD de Category.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::categories.index');
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
        // $categories = $this->categoryRepository->all();
        $categories = Category::with(['mantAssetType'])->latest()->get();
        return $this->sendResponse($categories->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes, incluyendo los eliminados
     *
     * @author German Gonzalez V. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function allCategoryFull() {
        $categories = $this->categoryRepository->all();
        // $categories = Category::all();
        return $this->sendResponse($categories->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryRequest $request) {

        $input = $request->all();

        $category = $this->categoryRepository->create($input);

        // Obtiene la categoría asignada
        $category->mantAssetType;

        return $this->sendResponse($category->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryRequest $request) {

        $input = $request->all();

        /** @var Category $category */
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $category = $this->categoryRepository->update($input, $id);

        // Obtiene la categoría asignada
        $category->mantAssetType;

        return $this->sendResponse($category->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Category del almacenamiento
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

        /** @var Category $category */
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $category->delete();

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
        $fileName = time().'-'.trans('categories').'.'.$fileType;

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
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCategoriesAsset($id) {
        
        $categories = Category::withTrashed()->where("mant_asset_type_id", $id)->latest()->get();
        return $this->sendResponse($categories->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene el número de veces que esta relacionada una categoría con alguna categoría
     *
     * @author German Gonzalez V. - Sep. 14 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getCountCategory(Request $request) {
        // Obtiene el número de veces que esta relacionada una categoría (recibido por parámetro), con una autorización
        $countCategory = AuthorizedCategories::where("mant_category_id", $request->idCategory)->count();

        // Obtiene el número de veces que esta relacionada una categoría (recibido por parámetro), con una hoja de vida
        $countCategory_AssetYellow = ResumeMachineryVehiclesYellow::where("mant_category_id", $request->idCategory)->count();
        // Obtiene el número de veces que esta relacionada una categoría (recibido por parámetro), con un hoja de vida
        $countCategory_AssetInventory = ResumeInventoryLeca::where("mant_category_id", $request->idCategory)->count();
        // Obtiene el número de veces que esta relacionada una categoría (recibido por parámetro), con una hoja de vida
        $countCategory_AssetMaquinariaLeca = ResumeEquipmentMachineryLeca::where("mant_category_id", $request->idCategory)->count();
        // Obtiene el número de veces que esta relacionada una categoría (recibido por parámetro), con una hoja de vida
        $countCategory_AssetMaquinaria = ResumeEquipmentMachinery::where("mant_category_id", $request->idCategory)->count();
        // Obtiene el número de veces que esta relacionada una categoría (recibido por parámetro), con una hoja de vida
        $countCategory_AssetLeca = ResumeEquipmentLeca::where("mant_category_id", $request->idCategory)->count();

        // Mensaje por defecto para retornar al usuario
        $mensaje = "";
        // Condición para validar si la categoría esta relacionada con alguna autorización
        if($countCategory > 0) {
            $mensaje = "No se puede eliminar esta categoría ya que se esta relacionada con una autorización de creación de activos";
        }
        
        if($countCategory_AssetYellow > 0) {
            $countCategory = $countCategory_AssetYellow;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida de maquinaria amarilla";
            } else {
                $mensaje = "No se puede eliminar esta categoría ya que se esta relacionada con una hoja de vida de maquinaria amarilla";
            }
        } else if($countCategory_AssetInventory > 0) {
            $countCategory = $countCategory_AssetInventory;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida del aseguramiento metrológico";
            } else {
                $mensaje = "No se puede eliminar esta categoría ya que se esta relacionada con una hoja de vida del aseguramiento metrológico";
            }
        } else if($countCategory_AssetMaquinariaLeca > 0) {
            $countCategory = $countCategory_AssetMaquinariaLeca;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida de plantas y medidores";
            } else {
                $mensaje = "No se puede eliminar esta categoría ya que se esta relacionada con una hoja de vida de plantas y medidores";
            }
        } else if($countCategory_AssetMaquinaria > 0) {
            $countCategory = $countCategory_AssetMaquinaria;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida de equipos menores";
            } else {
                $mensaje = "No se puede eliminar esta categoría ya que se esta relacionada con una hoja de vida de equipos menores";
            }
        } else if($countCategory_AssetLeca > 0) {
            $countCategory = $countCategory_AssetLeca;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida del equipamiento LECA";
            } else {
                $mensaje = "No se puede eliminar esta categoría ya que se esta relacionada con una hoja de vida del equipamiento LECA";
            }
        }

        // Retorna el resultado obtenido anteriormente
        return $this->sendResponse($countCategory, $mensaje);
    }

}
