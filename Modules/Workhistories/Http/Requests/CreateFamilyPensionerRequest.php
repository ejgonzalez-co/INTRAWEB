<?php

namespace Modules\Workhistories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Workhistories\Models\FamilyPensioner;

class CreateFamilyPensionerRequest extends FormRequest
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
        return FamilyPensioner::$rules;
    }
}
