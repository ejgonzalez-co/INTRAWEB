<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class InternalAnnotation
 * @package Modules\Correspondence\Models
 * @version March 10, 2022, 5:34 pm -05
 *
 * @property Modules\Correspondence\Models\CorrespondenceInternal $correspondenceInternal
 * @property string $content
 * @property string $users_name
 * @property integer $correspondence_internal_id
 */
class InternalAnnotation extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_internal_annotation';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'content',
        'users_name',
        'correspondence_internal_id',
        'attached',
        'users_id',
        'leido_por'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'content' => 'string',
        'users_name' => 'string',
        'correspondence_internal_id' => 'integer'
    ];

    protected $appends = [
        'date_format'
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'content' => 'nullable|string',
       
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
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function correspondenceInternal() {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceInternal::class, 'correspondence_internal_id');
    }

    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
