<?php

namespace Modules\DocumentaryClassification\Http\Requests;

use Modules\DocumentaryClassification\Models\criteriosBusqueda;
use Illuminate\Foundation\Http\FormRequest;

class UpdatecriteriosBusquedaRequest extends FormRequest
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
        $rules = criteriosBusqueda::$rules;
        
        return $rules;
    }
}
