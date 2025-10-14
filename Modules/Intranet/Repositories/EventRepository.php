<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Event;
use App\Repositories\BaseRepository;

/**
 * Class EventRepository
 * @package Modules\Intranet\Repositories
 * @version June 28, 2021, 1:58 am -05
*/

class EventRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'intranet_type_events_id',
        'type_category',
        'title',
        'description',
        'initial_date',
        'initial_hour',
        'end_hour',
        'duration',
        'state'
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
        return Event::class;
    }
}
