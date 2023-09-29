<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection
{
    protected $segment;

    public function __construct($segment)
    {
        $this->segment = $segment;
    }

    public function collection()
    {
        if ($this->segment != null) {
            return Product::where('segment_id', $this->segment)->get();
        } else {
            return Product::all();
        }
    }
}
