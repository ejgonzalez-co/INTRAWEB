<?php

namespace Modules\HelpTable\Models;

use Illuminate\Database\Eloquent\Model;

class TicAssetDocument extends Model
{
    public $table = 'ht_tic_assets_documents';

    public $fillable = [
        'ht_tic_assets_id',
        'users_id',
        'name',
        'content',
        'url_attachments'
    ];

    protected $casts = [
        'name' => 'string',
        'content' => 'string',
        'url_attachments' => 'string'
    ];

    public static array $rules = [
        'ht_tic_assets_id' => 'required',
        'users_id' => 'required',
        'name' => 'nullable|string|max:200',
        'content' => 'nullable|string',
        'url_attachments' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function htTicAssets(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\HelpTable\Models\HtTicAsset::class, 'ht_tic_assets_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\HelpTable\Models\User::class, 'users_id');
    }
}
