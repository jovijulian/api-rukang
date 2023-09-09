<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function logStatus()
    {
        return $this->hasMany(StatusLog::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
