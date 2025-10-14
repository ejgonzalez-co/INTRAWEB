<?php

namespace Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;


class AdditionSparePartActivity extends Model
{
    use SoftDeletes;

    public $table = 'mant_sn_additions_spare_parts_activities';

    public $fillable = [
        'type_request',
        'admin_observation',
        'provider_observation',
        'total_solicitud',
        'order_id',
        'request_id',
        'status',
        'admin_creator_id',
        'provider_creator_id'
    ];

    protected $casts = [
        'type_request' => 'string',
        'admin_observation' => 'string',
        'provider_observation' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'type_request' => 'nullable|string|max:26',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function Needs() {
        return $this->hasMany(\Modules\Maintenance\Models\AdditionNeed::class, 'addition_id');
    }

    public function providerCreator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Providers::class, 'provider_creator_id');
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Maintenance\Models\MantSnOrder::class, 'order_id');
    }

    public function RequestNeed(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Maintenance\Models\RequestNeed::class, 'request_id');
    }

    public function adminCreator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'admin_creator_id');
    }
}
