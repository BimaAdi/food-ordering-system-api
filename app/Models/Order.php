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

    /**
     * Get the order_menus for the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    /**
     * Get all the menus for the order.
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'order_menus', 'order_id', 'menu_id');
    }
}
