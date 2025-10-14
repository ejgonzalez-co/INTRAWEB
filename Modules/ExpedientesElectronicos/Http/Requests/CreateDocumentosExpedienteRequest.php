<?php

namespace Modules\ExpedientesElectronicos\Http\Requests;

use Modules\ExpedientesElectronicos\Models\DocumentosExpediente;
use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentosExpedienteRequest extends FormRequest
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
        return DocumentosExpediente::$rules;
    }
}
