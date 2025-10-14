<?php

namespace Modules\Intranet\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeMonitoringControl extends Model
{
    public $table = 'intranet_news_monitoring_control';

    public $fillable = [
        'intranet_news_id',
        'name',
        'fullname',
        'user_id',
        'fechas_visualizaciones'
    ];

    protected $casts = [
        'name' => 'string',
        'fullname' => 'string',
        'user_id' => 'integer',
        'fechas_visualizaciones' => 'string'
    ];

    public static array $rules = [
        'intranet_news_id' => 'required',
        'name' => 'nullable|string|max:60',
        'fullname' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function intranetNews(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\IntranetNews::class, 'intranet_news_id');
    }
}
