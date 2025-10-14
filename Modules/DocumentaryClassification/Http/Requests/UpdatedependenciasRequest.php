<?php

namespace Modules\DocumentaryClassification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\DocumentaryClassification\Models\dependencias;

class UpdatedependenciasRequest extends FormRequest
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
        $rules = dependencias::$rules;
        
        return $rules;
    }
}
