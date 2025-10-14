<?php

namespace Modules\DocumentosElectronicos\Http\Requests;

use Modules\DocumentosElectronicos\Models\TipoDocumento;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTipoDocumentoRequest extends FormRequest
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
        $rules = TipoDocumento::$rules;
        
        return $rules;
    }
}
