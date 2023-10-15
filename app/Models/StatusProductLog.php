<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusProductLog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */

    protected $table = 'status_product_logs';
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
        'id',
        'product_id',
        'status_id',
        'status_name',
        'status_date',
        'status_photo',
        'status_photo2',
        'status_photo3',
        'status_photo4',
        'status_photo5',
        'status_photo6',
        'status_photo7',
        'status_photo8',
        'status_photo9',
        'status_photo10',
        'note',
        'shipping_id',
        'shipping_name',
        'number_plate',
        'upload_signature',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function statuses()
    {
        return $this->hasMany(StatusProduct::class);
    }

    public function locationLogs()
    {
        return $this->hasMany(LocationProductLog::class);
    }
}
