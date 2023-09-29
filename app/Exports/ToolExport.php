<?php

namespace App\Exports;

use App\Models\Tool;
use Maatwebsite\Excel\Concerns\FromCollection;

class ToolExport implements FromCollection
{
    public function collection()
    {
        return Tool::all();
    }
}
