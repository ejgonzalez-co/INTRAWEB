<?php

namespace Modules\ContractualProcess\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ContractualProcess\Models\ProcessLeaders;

class UpdateProcessLeadersRequest extends FormRequest
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
        $rules = ProcessLeaders::$rules;
        
        return $rules;
    }
}
