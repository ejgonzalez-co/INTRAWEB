<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalReceivedRead extends Model
{
    public $table = 'external_received_read';

    public $fillable = [
        'users_name',
        'users_id',
        'access',
        'start',
        'year',
        'correspondence_external_id',
        'users_type'
    ];

    protected $casts = [
        'users_name' => 'string',
        'access' => 'string'
    ];

    public static array $rules = [
        'users_name' => 'required|string|max:255',
        'users_id' => 'required',
        'access' => 'required|string',
        'start' => 'required',
        'year' => 'required',
        'correspondence_external_id' => 'required',
        'created_at' => 'required',
        'updated_at' => 'required',
        'deleted_at' => 'required'
    ];

    
}
