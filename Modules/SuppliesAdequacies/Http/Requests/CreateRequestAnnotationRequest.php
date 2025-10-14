<?php

namespace Modules\SuppliesAdequacies\Http\Requests;

use Modules\SuppliesAdequacies\Models\RequestAnnotation;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequestAnnotationRequest extends FormRequest
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
        return RequestAnnotation::$rules;
    }
}
