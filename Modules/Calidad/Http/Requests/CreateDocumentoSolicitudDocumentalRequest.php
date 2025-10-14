<?php

namespace Modules\Calidad\Http\Requests;

use Modules\Calidad\Models\DocumentoSolicitudDocumental;
use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentoSolicitudDocumentalRequest extends FormRequest
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
        return DocumentoSolicitudDocumental::$rules;
    }
}
