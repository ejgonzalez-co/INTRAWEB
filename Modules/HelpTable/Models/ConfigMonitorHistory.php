<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ConfigMonitorHistory
 * @package Modules\HelpTable\Models
 * @version January 23, 2023, 11:38 am -05
 *
 * @property \Modules\HelpTable\Models\HtTicMonitorConfiguration $htTicMonitorConfigurations
 * @property integer $ht_tic_monitor_configurations_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $action
 */
class ConfigMonitorHistory extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_monitor_configurations_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'ht_tic_monitor_configurations_id',
        'user_id',
        'user_name',
        'action'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_monitor_configurations_id' => 'integer',
        'user_id' => 'integer',
        'user_name' => 'string',
        'action' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_monitor_configurations_id' => 'required',
        'user_id' => 'nullable',
        'user_name' => 'nullable|string|max:255',
        'action' => 'nullable|string|max:100',
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
    public function htTicMonitorConfigurations() {
        return $this->belongsTo(\Modules\HelpTable\Models\HtTicMonitorConfiguration::class, 'ht_tic_monitor_configurations_id');
    }
}
