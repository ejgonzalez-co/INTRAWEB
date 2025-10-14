<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\ResumeEquipmentLeca;
use App\Repositories\BaseRepository;

/**
 * Class ResumeEquipmentLecaRepository
 * @package Modules\Maintenance\Repositories
 * @version May 3, 2021, 5:48 pm -05
*/

class ResumeEquipmentLecaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_equipment',
        'internal_code_leca',
        'inventory_no',
        'mark',
        'serie',
        'model',
        'location',
        'software',
        'purchase_date',
        'commissioning_date',
        'date_withdrawal_service',
        'maker',
        'provider',
        'catalogue',
        'catalogue_location',
        'idiom',
        'instructive',
        'instructional_location',
        'magnitude_control',
        'consumables',
        'resolution',
        'accessories',
        'operation_range',
        'voltage',
        'use',
        'use_range',
        'allowable_error',
        'minimum_permissible_error',
        'environmental_operating_conditions',
        'dependencias_id',
        'mant_category_id',
        'responsable',
        'mant_asset_type_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ResumeEquipmentLeca::class;
    }
}
