<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
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

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'talent_id',
        'talent_name',
        'talent_phone_number',
        'order_date',
        'repair_time',
        'repair_address',
        'category_id',
        'category_name',
        'detail_requirement',
    ];
}
