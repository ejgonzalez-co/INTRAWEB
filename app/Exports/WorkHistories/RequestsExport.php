<?php

namespace App\Exports\WorkHistories;

use Modules\Workhistories\Models\WorkHistoriesActive;
use Maatwebsite\Excel\Concerns\FromCollection;

class RequestsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return WorkHistoriesActive::all();
    }
}
