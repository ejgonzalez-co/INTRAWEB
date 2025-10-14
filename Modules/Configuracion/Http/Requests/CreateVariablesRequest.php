<?php

namespace Modules\Configuracion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Configuracion\Models\Variables;

class CreateVariablesRequest extends FormRequest
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
        return Variables::$rules;
    }
}
