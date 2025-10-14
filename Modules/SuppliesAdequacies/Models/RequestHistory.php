<?php

namespace Modules\SuppliesAdequacies\Models;

use Eloquent as Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestHistory
 * @package Modules\SuppliesAdequacies\Models
 * @version November 12, 2024, 11:54 am -05
 *
 * @property \Modules\SuppliesAdequacies\Models\RequestsSuppliesAdjustement $requestsSuppliesAdjustements
 * @property integer $user_creator_id
 * @property integer $requests_supplies_adjustements_id
 * @property string|\Carbon\Carbon $expiration_date
 * @property string|\Carbon\Carbon $date_attention
 * @property string $tracking
 */
class RequestHistory extends Model {
    use SoftDeletes;

    public $table = 'requests_supplies_adjustements_histories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'user_creator_id',
        'requests_supplies_adjustements_id',
        'expiration_date',
        'date_attention',
        'tracking',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_creator_id' => 'integer',
        'requests_supplies_adjustements_id' => 'integer',
        'expiration_date' => 'datetime',
        'date_attention' => 'datetime',
        'tracking' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_creator_id' => 'required',
        'requests_supplies_adjustements_id' => 'required',
        'expiration_date' => 'nullable',
        'date_attention' => 'nullable',
        'tracking' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

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
    public function requestsSuppliesAdjustements() {
        return $this->belongsTo(\Modules\SuppliesAdequacies\Models\RequestSuppliesAdequacies::class, 'requests_supplies_adjustements_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function userCreator() {
        return $this->belongsTo(\App\User::class, 'user_creator_id')->with(["positions","dependencies"]);
    }
}
