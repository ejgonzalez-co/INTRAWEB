<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;
use App\Http\Controllers\AppBaseController;

/**
 * Class ReceivedHistory
 * @package Modules\Correspondence\Models
 * @version May 4, 2022, 3:36 am -05
 *
 * @property Modules\Correspondence\Models\Citizen $citizen
 * @property Modules\Correspondence\Models\TypesDocumentary $typeDocumentary
 * @property Modules\Correspondence\Models\Dependencia $dependency
 * @property Modules\Correspondence\Models\ExternalReceived $externalReceived
 * @property Modules\Intranet\Models\User $functionary
 * @property integer $external_received_id
 * @property integer $dependency_id
 * @property integer $functionary_id
 * @property integer $citizen_id
 * @property integer $type_documentary_id
 * @property string $dependency_name
 * @property string $functionary_name
 * @property string $citizen_name
 * @property string $user_name
 * @property string $consecutive
 * @property string $issue
 * @property integer $folio
 * @property integer $annexed
 * @property integer $channel
 * @property string $novelty
 * @property string $pqr
 * @property integer $receiving_channel
 * @property integer $document_pdf
 * @property string $attached_document
 * @property string $users_copies
 * @property string $users_shares
 * @property integer $year
 * @property integer $state
 */
class ReceivedHistory extends Model
{
        use SoftDeletes;

    public $table = 'external_received_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'external_received_id',
        'dependency_id',
        'functionary_id',
        'citizen_id',
        'type_documentary_id',
        'dependency_name',
        'functionary_name',
        'citizen_name',
        'citizen_document',
        'citizen_email',
        'citizen_users_id',
        'user_name',
        'consecutive',
        'issue',
        'folio',
        'annexed',
        'channel',
        'novelty',
        'pqr',
        'receiving_channel',
        'document_pdf',
        'attached_document',
        'users_copies',
        'users_shares',
        'year',
        'state',
        'npqr',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'observation_history',
        'user_id',
        'validation_code',
        'reason_return'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'external_received_id' => 'integer',
        'dependency_id' => 'integer',
        'functionary_id' => 'integer',
        'citizen_id' => 'integer',
        'type_documentary_id' => 'integer',
        'dependency_name' => 'string',
        'functionary_name' => 'string',
        'citizen_name' => 'string',
        'user_name' => 'string',
        'consecutive' => 'string',
        'issue' => 'string',
        'folio' => 'integer',
        'annexed' => 'string',
        'channel' => 'integer',
        'novelty' => 'string',
        'pqr' => 'string',
        'receiving_channel' => 'integer',
        'users_copies' => 'string',
        'users_shares' => 'string',
        'year' => 'integer',
        'state' => 'integer'
    ];

    protected $appends = [
        'type_documentary_name',
        'channel_name',
        'state_name',
        'date_format'
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'dependency_id' => 'nullable',
        'functionary_id' => 'nullable',
        'citizen_id' => 'nullable',
        'type_documentary_id' => 'nullable',
        'dependency_name' => 'nullable|string|max:120',
        'functionary_name' => 'nullable|string|max:120',
        'citizen_name' => 'nullable|string|max:120',
        'user_name' => 'nullable|string|max:120',
        'consecutive' => 'nullable|string|max:191',
        'issue' => 'nullable|string',
        'folio' => 'nullable|integer',
        'channel' => 'nullable|integer',
        'novelty' => 'nullable|string',
        'pqr' => 'nullable|string|max:45',
        'receiving_channel' => 'nullable|integer',
        'users_copies' => 'nullable|string',
        'users_shares' => 'nullable|string',
        'year' => 'nullable|integer',
        'state' => 'nullable|integer',
        'reason_return' => 'string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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
    public function citizen() {
        return $this->belongsTo(\Modules\Intranet\Models\Citizen::class, 'citizen_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function typeDocumentary() {
        return $this->belongsTo(\Modules\Correspondence\Models\TypeDocumentary::class, 'type_documentary_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependency() {
        return $this->belongsTo(\Modules\Correspondence\Models\Dependencia::class, 'dependency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function externalReceived() {
        return $this->belongsTo(\Modules\Correspondence\Models\ExternalReceived::class, 'external_received_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function functionary() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'functionary_id');
    }

    
    /**
     * Obtiene el nombre del tipo de documento
     * @return 
     */
    public function getTypeDocumentaryNameAttribute() {
        // Valida si el dato es diferente de vacio
        if (!empty($this->type_documentary_id)) {
            return $this->typeDocumentary->name;
        }
        return null;
    }

    /**
     * Obtiene el nombre del tipo de documento
     * @return 
     */
    public function getChannelNameAttribute() {
        // Valida si el dato es diferente de vacio
        if (!empty($this->channel)) {
            return AppBaseController::getObjectOfList(config('correspondence.external_received_channels'), 'id', $this->channel)->name;
        }
        return null;
    }

    /**
     * Obtiene el nombre del estado
     * @return 
     */
    public function getStateNameAttribute() {
        // Valida si el dato es diferente de vacio
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('correspondence.external_received_states'), 'id', $this->state)->name;
        }
        return null;
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'user_id');
    }
}
