<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tool extends Model
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
        'category_id',
        'category',
        'type',
        'tool_name',
        'serial_number',
        'amount',
        'note',
        'status_id',
        'status',
        'status_photo',
        'status_note',
        'shipping_id',
        'current_location',
        'shipping_name',
        'group_id',
        'group_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function statusToolMaterial()
    {
        return $this->belongsTo(StatusToolMaterial::class);
    }

    public function logs()
    {
        return $this->hasMany(StatusToolLog::class);
    }
}