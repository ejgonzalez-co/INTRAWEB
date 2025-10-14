<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ExternalCopyShare
 * @package Modules\Correspondence\Models
 * @version April 19, 2022, 6:27 pm -05
 *
 * @property Modules\Correspondence\Models\CorrespondenceExternal $correspondenceExternal
 * @property Modules\Intranet\Models\User $users
 * @property string $type
 * @property boolean $is_readed
 * @property string $times
 * @property string $name
 * @property integer $correspondence_external_id
 * @property integer $users_id
 */
class ExternalCopyShare extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_external_copy_share';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ['fullname'];

    
    public $fillable = [
        'type',
        'is_readed',
        'times',
        'name',
        'correspondence_external_id',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'is_readed' => 'boolean',
        'times' => 'string',
        'name' => 'string',
        'correspondence_external_id' => 'integer',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'nullable|string|max:45',
        'is_readed' => 'nullable|boolean',
        'times' => 'nullable|string',
        'name' => 'nullable|string',
        'correspondence_external_id' => 'required',
        'users_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function correspondenceExternal() {
        return $this->belongsTo(\Modules\Correspondence\Models\External::class, 'correspondence_external_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
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
