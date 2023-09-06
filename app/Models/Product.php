<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'segment_id',
        'segment_name',
        'barcode',
        'module_number',
        'bilah_number',
        'production_date',
        'shelf_number',
        '"1/0"',
        'nut_bolt',
        'description_id',
        'description',
        'delivery_date',
        'image',
        'category_id',
        'category',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_flag',
    ];

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function description()
    {
        return $this->belongsTo(Description::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
