<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\OptionalContactEmails;
use App\Repositories\BaseRepository;

/**
 * Class OptionalContactEmailsRepository
 * @package Modules\Maintenance\Repositories
 * @version March 12, 2021, 9:09 am -05
*/

class OptionalContactEmailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'mail',
        'phone',
        'observation',
        'mant_providers_id'
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
        return OptionalContactEmails::class;
    }
}
