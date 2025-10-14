<?php

namespace Modules\Leca\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Leca\Models\SampleTaking;

class UpdateSampleTakingRequest extends FormRequest
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
        $rules = SampleTaking::$rules;
        
        return $rules;
    }
}
