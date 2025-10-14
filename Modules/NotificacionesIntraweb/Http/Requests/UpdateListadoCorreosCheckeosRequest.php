<?php

namespace Modules\NotificacionesIntraweb\Http\Requests;

use Modules\NotificacionesIntraweb\Models\ListadoCorreosCheckeos;
use Illuminate\Foundation\Http\FormRequest;

class UpdateListadoCorreosCheckeosRequest extends FormRequest
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
        $rules = ListadoCorreosCheckeos::$rules;
        
        return $rules;
    }
}
