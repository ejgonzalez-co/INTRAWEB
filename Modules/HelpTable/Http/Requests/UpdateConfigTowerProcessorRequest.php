<?php

namespace Modules\HelpTable\Http\Requests;

use Modules\HelpTable\Models\ConfigTowerProcessor;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigTowerProcessorRequest extends FormRequest
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
        $rules = ConfigTowerProcessor::$rules;
        
        return $rules;
    }
}
