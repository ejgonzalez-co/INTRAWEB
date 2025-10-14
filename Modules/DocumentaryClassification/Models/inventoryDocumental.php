<?php

namespace Modules\DocumentaryClassification\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\DocumentaryClassification\Models\dependencias;
use Modules\DocumentaryClassification\Models\CriteriosBusquedaValue;
use DateTimeInterface;

/**
 * Class inventoryDocumental
 * @package Modules\DocumentaryClassification\Models
 * @version March 31, 2023, 3:07 am -05
 *
 * @property Modules\DocumentaryClassification\Models\CdDependecia $idDependencias
 * @property Modules\DocumentaryClassification\Models\CdSeriesSubseries $seriesOsubseries
 * @property \Illuminate\Database\Eloquent\Collection $cdInventoryDocuments
 * @property integer $id_dependencias
 * @property integer $id_series_subseries
 * @property string $description_expedient
 * @property string $folios
 * @property string $clasification
 * @property string $consultation_frequency
 * @property string $soport
 * @property string $shelving
 * @property string $tray
 * @property string $box
 * @property string $file
 * @property string $book
 * @property time $date_initial
 * @property time $date_finish
 * @property string $range_initial
 * @property string $range_finish
 * @property string $observation
 */
class inventoryDocumental extends Model
{
        use SoftDeletes;

    public $table = 'cd_inventory_documental';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'id_dependencias',
        'id_series_subseries',
        'description_expedient',
        'folios',
        'clasification',
        'consultation_frequency',
        'soport',
        'shelving',
        'tray',
        'box',
        'file',
        'book',
        'date_initial',
        'date_finish',
        'range_initial',
        'range_finish',
        'observation',
        'attachment',
        'metadatos',
        'nombre_dependencia',
        'codigo_dependencia',
        'no_serie',
        'no_subserie',
        'nombre_serie',
        'nombre_subserie'
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_dependencias' => 'integer',
        'id_series_subseries' => 'integer',
        'description_expedient' => 'string',
        'folios' => 'string',
        'clasification' => 'string',
        'consultation_frequency' => 'string',
        'soport' => 'string',
        'shelving' => 'string',
        'tray' => 'string',
        'box' => 'string',
        'file' => 'string',
        'book' => 'string',
        'range_initial' => 'string',
        'range_finish' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_dependencias' => 'required|integer',
        'id_series_subseries' => 'required|integer',
        'description_expedient' => 'nullable|string|max:500',
        'folios' => 'nullable|string|max:45',
        'clasification' => 'nullable|string|max:45',
        'consultation_frequency' => 'nullable|string|max:45',
        'soport' => 'nullable|string|max:45',
        'shelving' => 'nullable|string|max:45',
        'tray' => 'nullable|string|max:45',
        'box' => 'nullable|string|max:45',
        'file' => 'nullable|string|max:45',
        'book' => 'nullable|string|max:45',
        'date_initial' => 'nullable',
        'date_finish' => 'nullable',
        'range_initial' => 'nullable|string|max:45',
        'range_finish' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'total_documents',
        'metadatos_text',
        'texto_criterio',
        'lista_criterio',
        'contenido_criterio',
        'numero_criterio',
        'fecha_criterio'
    ];

    public function getTotalDocumentsAttribute() {
        if($this->attachment){
            $documentos = explode(',', $this->attachment);

            // Cuenta el número de documentos
            $totalDocumentos = count($documentos);
        }else{
            $totalDocumentos = 0;
        }
        return $totalDocumentos;
    }

    public function getMetadatosTextAttribute() {
        return  json_encode($this->metadatos);
    }

    public function getTextoCriterioAttribute() {
        $data = CriteriosBusquedaValue::where('cd_inventory_documental_id',$this->id)->get()->first();
        if ($data != null) {
            return $data['texto_criterio'];
        }else{
            return '';
        }
    }

    public function getListaCriterioAttribute() {
        $data = CriteriosBusquedaValue::where('cd_inventory_documental_id',$this->id)->get()->first();
        if ($data != null) {
            return $data['lista_criterio'];
        } else {
            return '';
        }
        
    }

    public function getContenidoCriterioAttribute() {
        $data = CriteriosBusquedaValue::where('cd_inventory_documental_id',$this->id)->get()->first();
        if ($data != null) {
            return $data['contenido_criterio'];
        } else {
            return '';
        }
        
    }

    public function getNumeroCriterioAttribute() {
        $data = CriteriosBusquedaValue::where('cd_inventory_documental_id',$this->id)->get()->first();
        if ($data != null) {
            return $data['numero_criterio'];
        } else {
            return '';
        }
        
    }

    public function getFechaCriterioAttribute() {
        $data = CriteriosBusquedaValue::where('cd_inventory_documental_id',$this->id)->get()->first();
        if ($data != null) {
            return $data['fecha_criterio'];
        } else {
            return '';
        }
        
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencia() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\dependencias::class, 'id_dependencias');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function seriesOsubseries() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\seriesSubSeries::class, 'id_series_subseries');
    }


    public function CriteriosBusquedaValue()
    {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\CriteriosBusquedaValue::class, 'cd_inventory_documental_id');
    }

 
}
