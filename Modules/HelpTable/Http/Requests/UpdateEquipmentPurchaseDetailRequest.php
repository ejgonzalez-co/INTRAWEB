<?php

namespace Modules\HelpTable\Http\Requests;

use Modules\HelpTable\Models\EquipmentPurchaseDetail;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentPurchaseDetailRequest extends FormRequest
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
        $rules = EquipmentPurchaseDetail::$rules;
        
        return $rules;
    }
}
