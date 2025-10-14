<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicProvider;
use App\Repositories\BaseRepository;

/**
 * Class TicProviderRepository
 * @package Modules\HelpTable\Repositories
 * @version June 8, 2021, 9:34 am -05
*/

class TicProviderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'type_person',
        'document_type',
        'identification',
        'profession',
        'professional_card_number',
        'regime',
        'address',
        'phone',
        'cellular',
        'state',
        'ciiu_activities',
        'contract_start',
        'contract_end',
        'email'
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
        return TicProvider::class;
    }
}
