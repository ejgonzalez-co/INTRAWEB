<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class InternalChequeos extends Model
{
    public $table = 'correspondence_internal_chequeos';

    public $fillable = [
        'users_id',
        'correspondence_internal_id',
        'user_name',
        'fullname',
        'estado_check'
    ];

    protected $casts = [
        'user_name' => 'string',
        'fullname' => 'string',
        'estado_check' => 'string'
    ];

    public static array $rules = [
        'users_id' => 'required',
        'correspondence_internal_id' => 'required',
        'user_name' => 'nullable|string|max:150',
        'fullname' => 'nullable|string|max:16777215',
        'estado_check' => 'nullable|string|max:10',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
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

    public function correspondenceInternal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceInternal::class, 'correspondence_internal_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\User::class, 'users_id');
    }
}
