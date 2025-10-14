<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ReportManagement
 * @package Modules\Leca\Models
 * @version December 7, 2022, 2:19 pm -05
 *
 * @property Modules\Leca\Models\LcCustomer $lcCustomers
 * @property Modules\Leca\Models\LcSampleTaking $lcSampleTaking
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcRmAttachments
 * @property \Illuminate\Database\Eloquent\Collection $lcRmHistoryReports
 * @property integer $lc_sample_taking_id
 * @property integer $users_id
 * @property integer $lc_customers_id
 * @property string $user_name
 * @property string $consecutive
 * @property string $status
 * @property string $coments_consecutive
 */
class ReportManagement extends Model {
    use SoftDeletes;

    public $table = 'lc_rm_report_management';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    

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
        'user_name' => 'string',
        'consecutive' => 'string',
        'status' => 'string',
        'date_report'=> 'string',
        'query_report'=> 'string',
        'coments_consecutive' => 'string',
        'nex_consecutiveIC'=> 'string',
        'nex_consecutiveIE'=> 'string',
    ];


    public $fillable = [
        'lc_sample_taking_id',
        'name_customer',
        'users_id',
        'lc_customers_id',
        'user_name',
        'consecutive',
        'status',
        'coments_consecutive',
        'date_report',
        'query_report',
        'nex_consecutiveIC',
        'nex_consecutiveIE',
        'mail_customer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_sample_taking_id' => '',
        'users_id' => '',
        'lc_customers_id' => '',
        'mail_customer' => '',
        'user_name' => 'nullable|string|max:255',
        'name_customer'=> 'nullable|string|max:255',
        'consecutive' => 'nullable|string|max:45',
        'status' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'coments_consecutive' => 'nullable|string|max:45'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historyReports() {
        return $this->hasMany(\Modules\Leca\Models\HistoryReport::class, 'lc_rm_report_management_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function attachments() {
        return $this->hasMany(\Modules\Leca\Models\ReporManagementAttachment::class, 'lc_rm_report_management_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcCustomers() {
        return $this->belongsTo(\Modules\Leca\Models\Customers::class, 'lc_customers_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function SampleTaking() {
        return $this->belongsTo(\Modules\Leca\Models\SampleTaking::class, 'lc_sample_taking_id');
    }

   
    
}
