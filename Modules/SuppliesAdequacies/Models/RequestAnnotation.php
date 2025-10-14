<?php

namespace Modules\SuppliesAdequacies\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAnnotation extends Model
{
    public $table = 'requests_supplies_adjustements_annotations';

    public $fillable = [
        'requests_supplies_adjustements_id',
        'users_id',
        'content',
        'leido_por',
        'attached'
    ];

    protected $casts = [
        'content' => 'string',
        'leido_por' => 'string',
        'attached' => 'string'
    ];

    public static array $rules = [
        'requests_supplies_adjustements_id' => 'required',
        'users_id' => 'required',
        'content' => 'nullable|string',
        'leido_por' => 'nullable|string|max:255',
        'attached' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = [
        'date_format'
    ];

        /**
     * Append
     *
     * @return void
     */
    public function getDateFormatAttribute() {

        $dateFormat['day'] = $this->created_at->format('d');
        $dateFormat['year'] = $this->created_at->format('Y');
        $dateFormat['hour'] = $this->created_at->format('H:i:s');
        $months = [
            1 => 'ENE',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'ABR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGO',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DIC',
        ];
    
        $dateFormat['month'] = $months[date('n', strtotime($this->created_at))];

        $months = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];
        $dateFormat['monthcompleto'] = $months[date('n', strtotime($this->created_at))];

        return $dateFormat;

    }

    public function requestsSuppliesAdjustements(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\SuppliesAdequacies\Models\RequestsSuppliesAdjustement::class, 'requests_supplies_adjustements_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }
}
