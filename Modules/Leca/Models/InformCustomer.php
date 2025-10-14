<?php

namespace Modules\leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InformCustomer
 * @package Modules\leca\Models
 * @version March 2, 2023, 11:39 am -05
 *
 * @property Modules\leca\Models\LcCustomer $lcCustomers
 * @property Modules\leca\Models\LcSampleTaking $lcSampleTaking
 * @property Modules\leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcRmAttachments
 * @property \Illuminate\Database\Eloquent\Collection $lcRmHistoryReports
 * @property integer $lc_sample_taking_id
 * @property integer $users_id
 * @property integer $lc_customers_id
 * @property string $consecutive
 * @property integer $nex_consecutiveIC
 * @property integer $nex_consecutiveIE
 * @property string $name_customer
 * @property string $mail_customer
 * @property string $coments_consecutive
 * @property string $date_report
 * @property string $user_name
 * @property string $status
 * @property string $query_report
 */
class InformCustomer extends Model {
    use SoftDeletes;

    public $table = 'lc_rm_report_management';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_sample_taking_id',
        'users_id',
        'lc_customers_id',
        'consecutive',
        'nex_consecutiveIC',
        'nex_consecutiveIE',
        'name_customer',
        'mail_customer',
        'coments_consecutive',
        'date_report',
        'user_name',
        'status',
        'query_report'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_sample_taking_id' => 'integer',
        'users_id' => 'integer',
        'lc_customers_id' => 'integer',
        'consecutive' => 'string',
        'nex_consecutiveIC' => 'integer',
        'nex_consecutiveIE' => 'integer',
        'name_customer' => 'string',
        'mail_customer' => 'string',
        'coments_consecutive' => 'string',
        'date_report' => 'string',
        'user_name' => 'string',
        'status' => 'string',
        'query_report' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_sample_taking_id' => 'required',
        'users_id' => 'required',
        'lc_customers_id' => 'required',
        'consecutive' => 'nullable|string|max:45',
        'nex_consecutiveIC' => 'nullable',
        'nex_consecutiveIE' => 'nullable',
        'name_customer' => 'nullable|string|max:255',
        'mail_customer' => 'nullable|string|max:255',
        'coments_consecutive' => 'nullable|string',
        'date_report' => 'nullable|string|max:45',
        'user_name' => 'nullable|string|max:255',
        'status' => 'nullable|string|max:45',
        'query_report' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcCustomers() {
        return $this->belongsTo(\Modules\leca\Models\Customers::class, 'lc_customers_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSampleTaking() {
        return $this->belongsTo(\Modules\leca\Models\SampleTaking::class, 'lc_sample_taking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcRmAttachments() {
        return $this->hasMany(\Modules\leca\Models\RmAttachment::class, 'lc_rm_report_management_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcRmHistoryReports() {
        return $this->hasMany(\Modules\leca\Models\RmHistoryReport::class, 'lc_rm_report_management_id');
    }
}
