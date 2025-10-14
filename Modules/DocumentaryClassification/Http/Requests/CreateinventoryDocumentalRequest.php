<?php

namespace Modules\DocumentaryClassification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\DocumentaryClassification\Models\inventoryDocumental;

class CreateinventoryDocumentalRequest extends FormRequest
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
        return inventoryDocumental::$rules;
    }
}
