<?php

namespace Modules\PQRS\Http\Requests;

use Modules\PQRS\Models\PQRTipoSolicitud;
use Illuminate\Foundation\Http\FormRequest;

class CreatePQRTipoSolicitudRequest extends FormRequest
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
        return PQRTipoSolicitud::$rules;
    }
}
