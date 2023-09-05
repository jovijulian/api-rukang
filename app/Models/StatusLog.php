<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusLog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */

    protected $table = 'status_logs';
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
        'product_id',
        'process_id',
        'process_name',
        'status_date',
        'process_attachment',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function processes()
    {
        return $this->hasMany(Process::class);
    }
}
