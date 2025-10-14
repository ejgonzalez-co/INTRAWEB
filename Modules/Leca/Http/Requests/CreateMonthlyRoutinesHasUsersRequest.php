<?php

namespace Modules\Leca\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Leca\Models\MonthlyRoutinesHasUsers;

class CreateMonthlyRoutinesHasUsersRequest extends FormRequest
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
        return MonthlyRoutinesHasUsers::$rules;
    }
}
