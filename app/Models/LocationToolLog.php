<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationToolLog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */

    protected $table = 'location_tool_logs';
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
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'status_tool_log_id',
        'product_id',
        'current_location',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function statusToolLog()
    {
        return $this->belongsTo(StatusToolLog::class);
    }
}