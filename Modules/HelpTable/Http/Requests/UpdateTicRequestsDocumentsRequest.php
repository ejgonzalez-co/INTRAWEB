<?php

namespace Modules\HelpTable\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\HelpTable\Models\TicRequestsDocuments;

class UpdateTicRequestsDocumentsRequest extends FormRequest
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
        $rules = TicRequestsDocuments::$rules;
        
        return $rules;
    }
}
