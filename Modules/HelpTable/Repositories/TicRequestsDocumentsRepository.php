<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicRequestsDocuments;
use App\Repositories\BaseRepository;

/**
 * Class TicRequestsDocumentsRepository
 * @package Modules\HelpTable\Repositories
 * @version May 23, 2024, 11:32 am -05
*/

class TicRequestsDocumentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ht_tic_requests_id',
        'name',
        'description',
        'url'
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
        return TicRequestsDocuments::class;
    }
}
