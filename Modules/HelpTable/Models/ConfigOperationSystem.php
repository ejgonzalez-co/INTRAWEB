<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ConfigOperationSystem
 * @package Modules\HelpTable\Models
 * @version February 28, 2023, 10:36 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htTicOperatingSystemConfigurationHistories
 * @property string $name
 * @property string $status
 */
class ConfigOperationSystem extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_operating_system_configuration';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:50',
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
    public function OperatingSystemConfigurationHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\ConfigOperationSystemHistory::class, 'ht_tic_operating_system_configuration_id');
    }
}
