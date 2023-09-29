<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromCollection;

class MaterialExport implements FromCollection
{
    public function collection()
    {
        return Material::all();
    }
}