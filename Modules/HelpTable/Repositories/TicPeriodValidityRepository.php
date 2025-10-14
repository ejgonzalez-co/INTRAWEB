<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicPeriodValidity;
use App\Repositories\BaseRepository;

/**
 * Class TicPeriodValidityRepository
 * @package Modules\HelpTable\Repositories
 * @version June 8, 2021, 11:52 am -05
*/

class TicPeriodValidityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return TicPeriodValidity::class;
    }
}
