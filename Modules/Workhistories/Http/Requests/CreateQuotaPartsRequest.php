<?php

namespace Modules\Workhistories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Workhistories\Models\QuotaParts;

class CreateQuotaPartsRequest extends FormRequest
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
        return QuotaParts::$rules;
    }
}
