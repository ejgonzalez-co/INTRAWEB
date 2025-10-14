<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ExternalVersions extends Model
{
    public $table = 'correspondence_external_versions';

    public $fillable = [
        'number_version',
        'users_name',
        'state',
        'observation',
        'document_pdf_temp',
        'correspondence_external_id',
        'users_id'
    ];

    protected $casts = [
        'number_version' => 'string',
        'users_name' => 'string',
        'state' => 'string',
        'observation' => 'string'
    ];

    public static array $rules = [
        'number_version' => 'nullable|string|max:45',
        'users_name' => 'nullable|string|max:45',
        'state' => 'nullable|string|max:45',
        'observation' => 'nullable|string|max:255',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'correspondence_external_id' => 'required',
        'users_id' => 'required'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function correspondenceExternal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceExternal::class, 'correspondence_external_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function externalSigns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalSigns::class, 'correspondence_external_versions_id')->with("users");
    }
}
