<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class ExternalAnnotation
 * @package Modules\Correspondence\Models
 * @version March 10, 2022, 5:34 pm -05
 *
 * @property Modules\Correspondence\Models\CorrespondenceExternal $correspondenceExternal
 * @property string $content
 * @property string $users_name
 * @property integer $correspondence_external_id
 */
class ExternalAnnotation extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_external_annotation';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'content',
        'users_name',
        'correspondence_external_id',
        'attached',
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
        'content' => 'string',
        'users_name' => 'string',
        'correspondence_external_id' => 'integer'
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
        'content' => 'nullable|string',
       
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function correspondenceExternal() {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceExternal::class, 'correspondence_external_id');
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
        // return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id')->select('url_img_profile')->whereNotNull('url_img_profile');

    }
}
