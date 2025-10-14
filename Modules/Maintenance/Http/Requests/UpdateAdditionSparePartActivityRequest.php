<?php

namespace Modules\Maintenance\Http\Requests;

use Modules\Maintenance\Models\AdditionSparePartActivity;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdditionSparePartActivityRequest extends FormRequest
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
        $rules = AdditionSparePartActivity::$rules;
        
        return $rules;
    }
}
