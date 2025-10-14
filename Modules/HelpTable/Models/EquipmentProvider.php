<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EquipmentProvider
 * @package Modules\HelpTable\Models
 * @version January 27, 2023, 8:03 am -05
 *
 * @property \Modules\HelpTable\Models\User $users
 * @property integer $users_id
 * @property string $identification_number
 * @property string $contract_number
 * @property string $fullname
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $contract_start_date
 * @property string $contract_end_date
 * @property string $status
 * @property string $observations
 */
class EquipmentProvider extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_equipment_providers';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'identification_number',
        'contract_number',
        'fullname',
        'email',
        'phone',
        'address',
        'contract_start_date',
        'contract_end_date',
        'status',
        'status_system',
        'observations',
        'pin',
        'password'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'identification_number' => 'string',
        'contract_number' => 'string',
        'fullname' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'address' => 'string',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'status' => 'string',
        'status_system' => 'string',
        'observations' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'identification_number' => 'nullable|string|max:35',
        'contract_number' => 'nullable|string|max:50',
        'fullname' => 'nullable|string|max:80',
        'email' => 'nullable|string|max:40',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:40',
        'contract_start_date' => 'nullable',
        'contract_end_date' => 'nullable',
        'status' => 'nullable|string|max:8',
        'observations' => 'nullable|string'
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
}
