<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\DocumentsAssets;
use App\Repositories\BaseRepository;

/**
 * Class DocumentsAssetsRepository
 * @package Modules\Maintenance\Repositories
 * @version March 22, 2021, 11:39 am -05
*/

class DocumentsAssetsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'url_document',
        'form_type',
        'mant_resume_machinery_vehicles_yellow_id'
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
        return DocumentsAssets::class;
    }
}
