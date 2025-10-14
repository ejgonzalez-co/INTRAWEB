<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateCategoryRequest;
use Modules\Intranet\Http\Requests\UpdateCategoryRequest;
use Modules\Intranet\Repositories\CategoryRepository;
use Modules\Intranet\Models\Category;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use Illuminate\Support\Str;

/**
 * Descripcion de la clase
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
        if(Auth::user()->hasRole(["Administrador intranet","Administrador intranet de descargas","Administrador intranet de eventos","Administrador intranet de encuestas"])){
            return view('intranet::categories.index');
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
        $categories = $this->categoryRepository->all();
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

        // $request->validate([
        //     'name' => 'unique:intranet_categories'
        // ]);

        try {
            // Inserta el registro en la base de datos
            $category = $this->categoryRepository->create([
                'name' => $input["name"],
                "slug" => Str::slug($input["name"])
            ]);

            // $categoriesNames = explode(",", $input["name"]);

            // $category = Category::register($categoriesNames);

            // dd( $category->toArray());
    
            // return CategoryResource::collection($categoriesSaved);

            return $this->sendResponse($category->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\CategoryController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\CategoryController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
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

        // Valida si el nombre nuevo es diferente al actual
        if ($category->name !== $input['name']) {
            // $request->validate([
            //     'name' => 'unique:intranet_categories'
            // ]);
        }

        if (empty($category)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $category = $this->categoryRepository->update([
                'name' => $input["name"],
                "slug" => Str::slug($input["name"])
            ], $id);
        
            return $this->sendResponse($category->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\CategoryController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\CategoryController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine().' Id: '.($category['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
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

        try {
            // Elimina el registro
            $category->delete();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\CategoryController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\CategoryController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine().' Id: '.($category['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
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
}
