<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
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

    protected $table = 'talents';

    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'about_me',
        'address',
        'rating',
        'category_id1',
        'category_name1',
        'category_id2',
        'category_name2',
        'category_id3',
        'category_name3',
        'category_id4',
        'category_name4',
        'image_profile',
        'image_profile2',
        'image_profile3',
        'image_profile4',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
