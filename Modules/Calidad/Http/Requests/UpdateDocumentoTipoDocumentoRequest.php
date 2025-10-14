<?php

namespace Modules\Calidad\Http\Requests;

use Modules\Calidad\Models\DocumentoTipoDocumento;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentoTipoDocumentoRequest extends FormRequest
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
        $rules = DocumentoTipoDocumento::$rules;
        
        return $rules;
    }
}
