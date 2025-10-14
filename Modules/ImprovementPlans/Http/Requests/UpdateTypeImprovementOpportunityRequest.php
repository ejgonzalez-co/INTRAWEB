<?php

namespace Modules\ImprovementPlans\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ImprovementPlans\Models\TypeImprovementOpportunity;

class UpdateTypeImprovementOpportunityRequest extends FormRequest
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
        $rules = TypeImprovementOpportunity::$rules;
        
        return $rules;
    }
}
