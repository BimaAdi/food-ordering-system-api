<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\TypeMenu;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $size = $request->input($key='size', $default='10');
        return Menu::with('type_menu')->paginate($size);
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
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'is_available' => ['required', 'boolean'],
            'img_url' => ['nullable',' string'],
            'type_menu_id' => ['required', 'integer', 'exists:type_menus,id']
        ]);

        // Insert Menu
        $new_menu = Menu::create($request->all());

        return response()->json($new_menu, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        return Menu::with('type_menu')->find($menu->id)->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        // validation
        $request->validate([
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'is_available' => ['required', 'boolean'],
            'img_url' => ['nullable',' string'],
            'type_menu_id' => ['required', 'integer', 'exists:type_menus,id']
        ]);
        
        // Update Menu
        $menu->fill($request->all())->save();

        return response()->json($menu, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json('', 204);
    }
}
