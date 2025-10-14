<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicAsset;
use App\Repositories\BaseRepository;

/**
 * Class TicAssetRepository
 * @package Modules\HelpTable\Repositories
 * @version June 4, 2021, 10:58 am -05
*/

class TicAssetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'consecutive',
        'name',
        'brand',
        'serial',
        'model',
        'inventory_plate',
        'description',
        'general_description',
        'purchase_date',
        'license_validity',
        'invoice_attachment',
        'location_address',
        'state',
        'monitor_id',
        'keyboard_id',
        'mouse_id',
        'operating_system',
        'operating_system_version',
        'operating_system_serial',
        'license_microsoft_office',
        'serial_licencia_microsoft_office',
        'processor',
        'ram',
        'hdd',
        'name_user',
        'provider_name',
        'ht_tic_period_validity_id',
        'ht_tic_type_assets_id',
        'ht_tic_provider_id',
        'users_id',
        'dependencias_id'
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
        return TicAsset::class;
    }
}
