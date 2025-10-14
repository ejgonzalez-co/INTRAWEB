<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicTypeAsset;
use App\Repositories\BaseRepository;

/**
 * Class TicTypeAssetRepository
 * @package Modules\HelpTable\Repositories
 * @version June 4, 2021, 2:17 pm -05
*/

class TicTypeAssetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'ht_tic_type_tic_categories_id'
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
        return TicTypeAsset::class;
    }
}
