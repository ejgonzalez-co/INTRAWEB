<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ExternalSigns extends Model
{
    public $table = 'correspondence_external_sign';

    public $fillable = [
        'observation',
        'users_name',
        'state',
        'correspondence_external_id',
        'users_id',
        'correspondence_external_versions_id',
        'hash',
        'ip'
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
        'correspondence_external_id' => 'required',
        'users_id' => 'required',
        'correspondence_external_versions_id' => 'required'
    ];

    public function correspondenceExternal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceExternal::class, 'correspondence_external_id');
    }

    public function correspondenceExternalVersions(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceExternalVersion::class, 'correspondence_external_versions_id');
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
