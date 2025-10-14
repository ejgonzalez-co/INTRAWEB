<?php

namespace Modules\Correspondence\Http\Requests;

use Modules\Correspondence\Models\InternalTypes;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInternalTypeRequest extends FormRequest
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
        $rules = InternalTypes::$rules;
        
        return $rules;
    }
}
