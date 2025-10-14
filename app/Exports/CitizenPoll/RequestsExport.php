<?php

namespace App\Exports\CitizenPoll;

use Modules\CitizenPoll\Models\Polls;
use Maatwebsite\Excel\Concerns\FromCollection;

class RequestsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Polls::all();
    }
}
