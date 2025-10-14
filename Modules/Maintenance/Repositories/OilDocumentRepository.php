<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\OilDocument;
use App\Repositories\BaseRepository;

/**
 * Class OilDocumentRepository
 * @package Modules\Maintenance\Repositories
 * @version December 20, 2021, 1:41 am -05
*/

class OilDocumentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_oils_id',
        'name',
        'description',
        'url_attachment'
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
        return OilDocument::class;
    }
}
