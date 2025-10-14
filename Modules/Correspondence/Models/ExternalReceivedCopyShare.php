<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ExternalReceivedCopyShare
 * @package Modules\Correspondence\Models
 * @version January 20, 2022, 1:27 am -05
 *
 * @property Modules\Correspondence\Models\ExternalReceived2022 $externalReceived
 * @property Modules\Intranet\Models\User $functionary
 * @property integer $external_received_id
 * @property string $name
 * @property boolean $read
 */
class ExternalReceivedCopyShare extends Model
{
        use SoftDeletes;

    public $table = 'external_received_copy_share';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ['fullname'];

    
    public $fillable = [
        'external_received_id',
        'type',
        'name',
        'read',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'external_received_id' => 'integer',
        'type' => 'string',
        'name' => 'string',
        'read' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'external_received_id' => 'required',
        'name' => 'nullable|string|max:120',
        'read' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function externalReceived() {
        return $this->belongsTo(\Modules\Correspondence\Models\ExternalReceived::class, 'external_received_id');
    }
    public function getFullnameAttribute()
    {
        // Combinar el nombre, cargo y dependencia en el nombre completo.
        return "{$this->name}";
    }

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
