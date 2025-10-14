<?php

namespace Modules\HelpTable\Http\Requests;

use Modules\HelpTable\Models\ConfigTowerHddCapacity;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigTowerHddCapacityRequest extends FormRequest
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
        $rules = ConfigTowerHddCapacity::$rules;
        
        return $rules;
    }
}
