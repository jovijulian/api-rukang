<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
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

    protected $table = 'reviews';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'talent_id',
        'talent_name',
        'transaction_id',
        'rating',
        'comment',
        'review_photo',
        'review_photo2',
        'review_photo3',
        'review_photo4',
    ];
}
