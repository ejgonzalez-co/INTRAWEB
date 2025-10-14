<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class InternalVersions extends Model
{
    public $table = 'correspondence_internal_versions';

    public $fillable = [
        'number_version',
        'users_name',
        'state',
        'observation',
        'document_pdf_temp',
        'correspondence_internal_id',
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
        'correspondence_internal_id' => 'required',
        'users_id' => 'required'
    ];
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
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function internalSigns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Correspondence\Models\InternalSigns::class, 'correspondence_internal_versions_id')->with("users");
    }
}
