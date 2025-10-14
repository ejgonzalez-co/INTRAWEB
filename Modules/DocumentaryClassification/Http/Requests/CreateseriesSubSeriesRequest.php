<?php

namespace Modules\DocumentaryClassification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\DocumentaryClassification\Models\seriesSubSeries;

class CreateseriesSubSeriesRequest extends FormRequest
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
        return seriesSubSeries::$rules;
    }
}
