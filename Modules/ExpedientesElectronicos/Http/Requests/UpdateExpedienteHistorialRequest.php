<?php

namespace Modules\ExpedientesElectronicos\Http\Requests;

use Modules\ExpedientesElectronicos\Models\ExpedienteHistorial;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpedienteHistorialRequest extends FormRequest
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
        $rules = ExpedienteHistorial::$rules;
        
        return $rules;
    }
}
