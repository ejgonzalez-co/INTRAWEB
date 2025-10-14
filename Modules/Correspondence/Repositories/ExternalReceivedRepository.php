<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\ExternalReceived;
use App\Repositories\BaseRepository;

/**
 * Class ExternalReceivedRepository
 * @packageModules\Correspondence\Repositories
 * @version January 13, 2022, 12:11 pm -05
*/

class ExternalReceivedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'dependency_id',
        'functionary_id',
        'citizen_id',
        'type_documentary_id',
        'dependency_name',
        'functionary_name',
        'citizen_name',
        'user_name',
        'consecutive',
        'issue',
        'folio',
        'annexed',
        'channel',
        'novelty',
        'observation',
        'finish_pqr',
        'receiving_channel',
        'document_pdf',
        'attached_document',
        'users_copies',
        'users_shares',
        'year',
        'state',
        'physical_address',
        'classification_serie',
        'classification_subserie',
        'classification_production_office'
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
        return ExternalReceived::class;
    }
}
