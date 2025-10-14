<?php

namespace Modules\HelpTable\Http\Requests;

use Modules\HelpTable\Models\ConfigTowerReference;
use Illuminate\Foundation\Http\FormRequest;

class CreateConfigTowerReferenceRequest extends FormRequest
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
        return ConfigTowerReference::$rules;
    }
}
