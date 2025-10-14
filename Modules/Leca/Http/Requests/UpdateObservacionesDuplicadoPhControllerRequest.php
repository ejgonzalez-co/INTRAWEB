<?php

namespace Modules\leca\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\leca\Models\ObservacionesDuplicadoPhController;

class UpdateObservacionesDuplicadoPhControllerRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = ObservacionesDuplicadoPhController::$rules;
        
        return $rules;
    }
}
