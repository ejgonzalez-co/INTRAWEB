<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class ReceivedAnnotation
 * @package Modules\Correspondence\Models
 * @version April 25, 2022, 4:19 am -05
 *
 * @property Modules\Intranet\Models\User $user
 * @property integer $external_received_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $annotation
 */
class ReceivedAnnotation extends Model
{
        use SoftDeletes;

    public $table = 'external_received_annotations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'external_received_id',
        'users_id',
        'users_name',
        'annotation',
        'users_id',
        'attached',
        'leido_por'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'external_received_id' => 'integer',
        'user_id' => 'integer',
        'user_name' => 'string',
        'annotation' => 'string'
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

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_name' => 'nullable|string|max:120',
        'annotation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'user_id');
    }

             
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

}
