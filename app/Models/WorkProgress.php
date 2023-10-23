<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkProgress extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $table = 'work_progresses';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'process_name',
        'photo_01',
        'photo_02',
        'photo_03',
        'photo_04',
        'photo_05',
        'photo_06',
        'photo_07',
        'photo_08',
        'photo_09',
        'photo_10',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
