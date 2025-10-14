<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ConfigMonitor
 * @package Modules\HelpTable\Models
 * @version January 23, 2023, 11:31 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htTicMonitorConfigurationsHistories
 * @property string $brand_name
 * @property string $status
 */
class ConfigMonitor extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_monitor_configurations';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'brand_name',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'brand_name' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'brand_name' => 'nullable|string|max:80',
        'status' => 'nullable|string|max:8',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function MonitorConfigurationsHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\ConfigMonitorHistory::class, 'ht_tic_monitor_configurations_id');
    }
}
