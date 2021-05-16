<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_number',
        'order_number',
        'waiter_id'
    ];

    /**
     * Get the order_menus for the order.
     */
    public function order_menus()
    {
        return $this->hasMany(OrderMenu::class);
    }
}
