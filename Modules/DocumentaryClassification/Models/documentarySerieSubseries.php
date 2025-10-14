<?php

namespace Modules\DocumentaryClassification\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class documentarySerieSubseries
 * @package Modules\DocumentaryClassification\Models
 * @version April 9, 2023, 4:43 pm -05
 *
 * @property Modules\DocumentaryClassification\Models\SeriesSubseries $seriesOsubseries
 * @property Modules\DocumentaryClassification\Models\TypeDocumentary $idTypeDocumentaries
 * @property integer $id_type_documentaries
 * @property integer $id_series_subseries
 */
class documentarySerieSubseries extends Model
{
        use SoftDeletes;

    public $table = 'cd_type_documentaries_has_cl_series_subseries';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'id_type_documentaries',
        'id_series_subseries',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_type_documentaries' => 'integer',
        'id_series_subseries' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_type_documentaries' => 'nullable|integer',
        'id_series_subseries' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = ['tipo_documental'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function seriesOsubseries() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\seriesSubSeries::class, 'id_series_subseries');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idTypeDocumentaries() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\typeDocumentaries::class, 'id_type_documentaries');
    }

    public function getTipoDocumentalAttribute(){
        return typeDocumentaries::where('id', $this->id_type_documentaries)->first();
    }
}
