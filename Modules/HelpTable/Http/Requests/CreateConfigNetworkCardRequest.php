<?php

namespace Modules\HelpTable\Http\Requests;

use Modules\HelpTable\Models\ConfigNetworkCard;
use Illuminate\Foundation\Http\FormRequest;

class CreateConfigNetworkCardRequest extends FormRequest
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
        return ConfigNetworkCard::$rules;
    }
}
