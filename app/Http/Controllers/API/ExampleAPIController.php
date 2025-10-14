<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateExampleAPIRequest;
use App\Http\Requests\API\UpdateExampleAPIRequest;
use App\Models\Example;
use App\Repositories\ExampleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class ExampleController
 * @package App\Http\Controllers\API
 */

class ExampleAPIController extends InfyOmBaseController
{
    /** @var  ExampleRepository */
    private $exampleRepository;

    public function __construct(ExampleRepository $exampleRepo)
    {
        $this->exampleRepository = $exampleRepo;
    }

    /**
     * Display a listing of the Example.
     * GET|HEAD /examples
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if (request()->has('sort')) {
            list($sortCol, $sortDir) = explode('|', request()->sort);
            $query = Example::orderBy($sortCol, $sortDir);
        } else {
            $query = Example::orderBy('created_at', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->where("position_id", "like", $value)
                  ->orWhere("name", "like", $value)
                  ->orWhere("email", "like", $value)
                  ->orWhere("email_verified_at", "like", $value)
                  ->orWhere("password", "like", $value)
                  ->orWhere("remember_token", "like", $value)
                  ->orWhere("url_img_profile", "like", $value)
                  ->orWhere("url_digital_signature", "like", $value)
                  ->orWhere("uuid", "like", $value);
            });
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        return response()->json($query->paginate($perPage));    
    }

    /**
     * Store a newly created Example in storage.
     * POST /examples
     *
     * @param CreateExampleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateExampleAPIRequest $request)
    {
        $input = $request->all();

        $examples = $this->exampleRepository->create($input);

        return $this->sendResponse($examples->toArray(), 'Example saved successfully');
    }

    /**
     * Display the specified Example.
     * GET|HEAD /examples/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Example $example */
        $example = $this->exampleRepository->find($id);

        if (empty($example)) {
            return Response::json(ResponseUtil::makeError('Example not found'), 400);
        }

        return $this->sendResponse($example->toArray(), 'Example retrieved successfully');
    }

    /**
     * Update the specified Example in storage.
     * PUT/PATCH /examples/{id}
     *
     * @param int $id
     * @param UpdateExampleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExampleAPIRequest $request)
    {
        $input = $request->all();

        /** @var Example $example */
        $example = $this->exampleRepository->find($id);

        if (empty($example)) {
            return Response::json(ResponseUtil::makeError('Example not found'), 400);
        }

        $example = $this->exampleRepository->update($input, $id);

        return $this->sendResponse($example->toArray(), 'Example updated successfully');
    }

    /**
     * Remove the specified Example from storage.
     * DELETE /examples/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Example $example */
        $example = $this->exampleRepository->find($id);

        if (empty($example)) {
            return Response::json(ResponseUtil::makeError('Example not found'), 400);
        }

        $example->delete();

        return $this->sendResponse($id, 'Example deleted successfully');
    }
}
