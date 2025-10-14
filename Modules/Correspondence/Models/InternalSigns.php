<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class InternalSigns extends Model
{
    public $table = 'correspondence_internal_sign';

    public $fillable = [
        'observation',
        'users_name',
        'state',
        'correspondence_internal_id',
        'users_id',
        'correspondence_internal_versions_id',
        'hash',
        'ip',
        'firma'
    ];

    protected $casts = [
        'observation' => 'string',
        'users_name' => 'string',
        'state' => 'string'
    ];

    public static array $rules = [
        'observation' => 'nullable|string|max:65535',
        'users_name' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:45',
        'correspondence_internal_id' => 'required',
        'users_id' => 'required',
        'correspondence_internal_versions_id' => 'required'
    ];

    public function correspondenceInternal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceInternal::class, 'correspondence_internal_id');
    }

    public function correspondenceInternalVersions(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceInternalVersion::class, 'correspondence_internal_versions_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
