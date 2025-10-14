<?php

namespace Modules\UpdateCitizenData\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UdcRequestHistory
 * @package Modules\UpdateCitizenData\Models
 * @version April 30, 2021, 5:42 pm -05
 *
 * @property Modules\UpdateCitizenData\Models\UdcRequest $udcRequest
 * @property string $payment_account_number
 * @property string $subscriber_quality
 * @property string $citizen_name
 * @property string $document_type
 * @property string $identification
 * @property string $gender
 * @property string $telephone
 * @property string $email
 * @property string $date_birth
 * @property string $users_name
 * @property integer $state
 * @property integer $udc_request_id
 */
class UdcRequestHistory extends Model
{
    use SoftDeletes;

    public $table = 'udc_request_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'payment_account_number',
        'subscriber_quality',
        'citizen_name',
        'document_type',
        'identification',
        'gender',
        'telephone',
        'email',
        'date_birth',
        'users_name',
        'state',
        'udc_request_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'payment_account_number' => 'string',
        'subscriber_quality' => 'string',
        'citizen_name' => 'string',
        'document_type' => 'string',
        'identification' => 'string',
        'gender' => 'string',
        'telephone' => 'string',
        'email' => 'string',
        'date_birth' => 'date',
        'users_name' => 'string',
        'state' => 'integer',
        'udc_request_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'payment_account_number' => 'nullable|string|max:255',
        'subscriber_quality' => 'nullable|string|max:255',
        'citizen_name' => 'nullable|string|max:255',
        'document_type' => 'nullable|string|max:255',
        'identification' => 'nullable|string|max:255',
        'gender' => 'nullable|string|max:45',
        'telephone' => 'nullable|string|max:45',
        'email' => 'nullable|string|max:255',
        'date_birth' => 'nullable',
        'users_name' => 'nullable|string|max:255',
        'state' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'udc_request_id' => 'required|integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function udcRequest()
    {
        return $this->belongsTo(\Modules\UpdateCitizenData\Models\UdcRequest::class, 'udc_request_id');
    }
}
