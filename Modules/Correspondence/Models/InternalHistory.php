<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;
use Carbon\Carbon;

/**
 * Class InternalHistory
 * @package Modules\Correspondence\Models
 * @version March 9, 2022, 5:51 pm -05
 *
 * @property Modules\Correspondence\Models\CorrespondenceInternalType $type
 * @property Modules\Correspondence\Models\Dependencia $dependencias
 * @property Modules\Correspondence\Models\CorrespondenceInternal $correspondenceInternal
 * @property Modules\Intranet\Models\User $users
 * @property string $consecutive
 * @property string $consecutive_order
 * @property string $state
 * @property string $title
 * @property string $content
 * @property string $folios
 * @property string $annexes
 * @property string $annexes_description
 * @property string $type_document
 * @property string $require_answer
 * @property string $answer_consecutive
 * @property string $template
 * @property string $editor
 * @property string $origen
 * @property string $recipients
 * @property string $document
 * @property string $document_pdf
 * @property string $from
 * @property string $from_id
 * @property string $dependency_from
 * @property string $elaborated
 * @property string $reviewd
 * @property string $approved
 * @property string $elaborated_names
 * @property string $reviewd_names
 * @property string $approved_names
 * @property string $creator_name
 * @property string $creator_dependency_name
 * @property string $elaborated_now
 * @property string $reviewd_now
 * @property string $approved_now
 * @property integer $number_review
 * @property string $observation
 * @property string $times_read
 * @property string $user_from_last_update
 * @property string $user_for_last_update
 * @property integer $year
 * @property boolean $internal_all
 * @property integer $type
 * @property integer $users_id
 * @property integer $dependencias_id
 * @property integer $correspondence_internal_id
 */
class InternalHistory extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_internal_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'consecutive',
        'consecutive_order',
        'state',
        'title',
        'content',
        'folios',
        'annexes',
        'annexes_description',
        'type_document',
        'require_answer',
        'answer_consecutive',
        'template',
        'editor',
        'origen',
        'recipients',
        'document',
        'document_pdf',
        'from',
        'from_id',
        'dependency_from',
        'elaborated',
        'reviewd',
        'approved',
        'elaborated_names',
        'reviewd_names',
        'approved_names',
        'creator_name',
        'creator_dependency_name',
        'elaborated_now',
        'reviewd_now',
        'approved_now',
        'number_review',
        'observation',
        'times_read',
        'user_from_last_update',
        'user_for_last_update',
        'year',
        'internal_all',
        'type',
        'users_id',
        'users_name',
        'dependencias_id',
        'correspondence_internal_id',
        'answer_consecutive_name',
        'annexes_digital',
        'hash_document_pdf',
        'observation_history',
        'validation_code',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'fecha_limite_respuesta',
        'responsable_respuesta',
        'responsable_respuesta_nombre',
        'estado_respuesta'


    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'consecutive' => 'string',
        'consecutive_order' => 'string',
        'state' => 'string',
        'title' => 'string',
        'content' => 'string',
        'folios' => 'string',
        'annexes' => 'string',
        'annexes_description' => 'string',
        'type_document' => 'string',
        'require_answer' => 'string',
        'answer_consecutive' => 'string',
        'template' => 'string',
        'editor' => 'string',
        'origen' => 'string',
        'recipients' => 'string',
        'document' => 'string',
        'document_pdf' => 'string',
        'from' => 'string',
        'from_id' => 'string',
        'dependency_from' => 'string',
        'elaborated' => 'string',
        'reviewd' => 'string',
        'approved' => 'string',
        'elaborated_names' => 'string',
        'reviewd_names' => 'string',
        'approved_names' => 'string',
        'creator_name' => 'string',
        'creator_dependency_name' => 'string',
        'elaborated_now' => 'string',
        'reviewd_now' => 'string',
        'approved_now' => 'string',
        'number_review' => 'integer',
        'observation' => 'string',
        'times_read' => 'string',
        'user_from_last_update' => 'string',
        'user_for_last_update' => 'string',
        'year' => 'integer',
        'internal_all' => 'boolean',
        'type' => 'integer',
        'users_id' => 'integer',
        'users_name' => 'string',
        'dependencias_id' => 'integer',
        'correspondence_internal_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'consecutive' => 'nullable|string|max:255',
        'consecutive_order' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:45',
        'title' => 'nullable|string',
        'content' => 'nullable|string',
        'folios' => 'nullable|string|max:255',
        'annexes' => 'nullable|string|max:255',
        'annexes_description' => 'nullable|string',
        'type_document' => 'nullable|string|max:255',
        'answer_consecutive' => 'nullable|string|max:255',
        'template' => 'nullable|string|max:255',
        'editor' => 'nullable|string|max:255',
        'origen' => 'nullable|string|max:45',
        'recipients' => 'nullable|string',
        'document' => 'nullable|string',
        'document_pdf' => 'nullable|string',
        'from' => 'nullable|string|max:255',
        'from_id' => 'nullable|string|max:45',
        'dependency_from' => 'nullable|string|max:255',
        'elaborated' => 'nullable|string|max:255',
        'reviewd' => 'nullable|string|max:255',
        'approved' => 'nullable|string|max:255',
        'elaborated_names' => 'nullable|string',
        'reviewd_names' => 'nullable|string',
        'approved_names' => 'nullable|string',
        'creator_name' => 'nullable|string|max:255',
        'creator_dependency_name' => 'nullable|string|max:255',
        'elaborated_now' => 'nullable|string|max:255',
        'reviewd_now' => 'nullable|string|max:255',
        'approved_now' => 'nullable|string|max:255',
        'number_review' => 'nullable|integer',
        'observation' => 'nullable|string',
        'times_read' => 'nullable|string',
        'user_from_last_update' => 'nullable|string|max:1000',
        'user_for_last_update' => 'nullable|string|max:1000',
        'year' => 'nullable|integer',
        'internal_all' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'type' => 'required',
        'users_id' => 'required',
        'dependencias_id' => 'required',
        'correspondence_internal_id' => 'required'
    ];

    protected $appends = [
        'date_format_day',
        'date_format_month',
        'date_format_year',
        'date_format_month_completo',
        'date_format_hour'

    ];


        /**
     * Append
     *
     * @return void
     */
    public function getDateFormatDayAttribute() {
        
        return $this->created_at->format('d');

    }

    public function getDateFormatYearAttribute() {
        
        return $this->created_at->format('Y');

    }
    
    public function getDateFormatHourAttribute() {
        
        return $this->created_at->format('H:i:s');

    }
    


    public function getDateFormatMonthAttribute() {
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
    
        return $months[date('n', strtotime($this->created_at))];
    }
            
    public function getDateFormatMonthCompletoAttribute() {
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
    
        return $months[date('n', strtotime($this->created_at))];
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
    public function type() {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceInternalType::class, 'type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Correspondence\Models\Dependencia::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function correspondenceInternal() {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceInternal::class, 'correspondence_internal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
