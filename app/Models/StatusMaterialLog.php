<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusMaterialLog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */

    protected $table = 'status_material_logs';
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
        'material_id',
        'status_id',
        'status',
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
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function statuses()
    {
        return $this->hasMany(StatusToolMaterial::class);
    }

    public function locationMaterialLogs()
    {
        return $this->hasMany(LocationMaterialLog::class);
    }
}
