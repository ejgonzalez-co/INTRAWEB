<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OptionalContactEmails
 * @package Modules\Maintenance\Models
 * @version March 12, 2021, 9:09 am -05
 *
 * @property Modules\Maintenance\Models\MantProvider $mantProviders
 * @property string $name
 * @property string $mail
 * @property string $phone
 * @property string $observation
 * @property integer $mant_providers_id
 */
class OptionalContactEmails extends Model
{
    use SoftDeletes;

    public $table = 'mant_optional_contact_emails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'mail',
        'phone',
        'observation',
        'mant_providers_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'mail' => 'string',
        'phone' => 'string',
        'observation' => 'string',
        'mant_providers_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_providers_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantProviders()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Providers::class, 'mant_providers_id')->withTrashed();
    }
}
