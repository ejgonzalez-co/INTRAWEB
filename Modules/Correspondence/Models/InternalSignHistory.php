<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class InternalSignHistory extends Model
{
    use SoftDeletes;

    public $table = 'correspondence_internal_sign_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];
    public $fillable = [
        'nombre',
        'hash',
        'ip',
        'id_documento',
        'tipo',
        'consecutivo'
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

}
