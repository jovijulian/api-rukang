<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModuleCompleteness extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $table = 'module_completeness';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'segment_id',
        'segment',
        'module_id',
        'module',
        'completeness',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function segments()
    {
        return $this->belongsTo(Segment::class, 'segment_id', 'id');
    }
}
