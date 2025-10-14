<?php

namespace Modules\ContractualProcess\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ContractualProcess\Models\PaaNeed;

class CreatePaaNeedRequest extends FormRequest
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
        return PaaNeed::$rules;
    }
}
