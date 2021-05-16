<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $size = $request->input($key='size', $default='10');
        return Order::with('order_menus')->paginate($size);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'table_number' => ['required', 'integer'],
            'order_menu' => ['required', 'array'],
            'order_menu.*.menu_id' => ['required', 'integer', 'distinct', 'exists:menus,id'],
            'order_menu.*.qty' => ['required', 'integer', 'gt:0'],
        ]);
        
        // Insert Order
        $new_order = Order::create([
            'table_number' => $request->get('table_number'),
            'order_number' => '111222333',
            'waiter_id' => Auth::id()
        ]);
        
        // Insert OrderMenu
        $new_order->order_menus()->saveMany(array_map(function($x) {
            return new OrderMenu([
                'menu_id' => $x['menu_id'],
                'qty' => $x['qty']
            ]);
        }, $request->get('order_menu')));
        $new_order->save();

        return response()->json($new_order, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return Order::with('order_menus')->find($order->id)->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        // validation
        $request->validate([
            'table_number' => ['required', 'integer'],
            'order_menu' => ['required', 'array'],
            'order_menu.*.menu_id' => ['required', 'integer', 'distinct', 'exists:menus,id'],
            'order_menu.*.qty' => ['required', 'integer', 'gt:0'],
        ]);

        // Update Order
        $order->table_number = $request->get('table_number');
        
        // Update OrderMenus
        $order->order_menus()->delete();
        $order->order_menus()->saveMany(array_map(function($x) {
            return new OrderMenu([
                'menu_id' => $x['menu_id'],
                'qty' => $x['qty']
            ]);
        }, $request->get('order_menu')));

        $order->save();

        return response()->json($order, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json('', 204);
    }
}
