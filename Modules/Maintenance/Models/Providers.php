<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Providers
 * @package Modules\Maintenance\Models
 * @version February 18, 2021, 11:48 am -05
 *
 * @property string $type_person
 * @property string $document_type
 * @property string $identification
 * @property string $name
 * @property string $mail
 * @property string $regime
 * @property string $phone
 * @property string $address
 * @property string $attached
 * @property string $state
 */
class Providers extends Model
{
    use SoftDeletes;

    public $table = 'mant_providers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'type_person',
        'document_type',
        'identification',
        'name',
        'mail',
        'regime',
        'phone',
        'address',
        'municipality',
        'department',
        'name_rep',
        'document_type_rep',
        'identification_rep',
        'phone_rep',
        'mail_rep',
        'description',
        'state',
        'mant_types_activity_id',
        'type_provider',
        'firma_proveedor'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type_person' => 'string',
        'document_type' => 'string',
        'identification' => 'string',
        'name' => 'string',
        'mail' => 'string',
        'regime' => 'string',
        'phone' => 'string',
        'address' => 'string',
        'municipality' => 'string',
        'department' => 'string',
        'name_rep' => 'string',
        'document_type_rep' => 'string',
        'identification_rep' => 'string',
        'phone_rep' => 'string',
        'mail_rep' => 'string',
        'description' => 'string',
        'state' => 'string',
        'mant_types_activity_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantSupportsProvider()
    {
        return $this->hasMany(\Modules\Maintenance\Models\SupportsProvider::class, 'mant_providers_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantTypesActivity()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\TypesActivity::class, 'mant_types_activity_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function optionalContactEmails()
    {
        return $this->hasMany(\Modules\Maintenance\Models\OptionalContactEmails::class, 'mant_providers_id');
    }

    
}
