<?php

namespace Modules\PQRS\Http\Requests;

use Modules\PQRS\Models\PQRAnotacion;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePQRAnotacionRequest extends FormRequest
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
        $rules = PQRAnotacion::$rules;
        
        return $rules;
    }
}
