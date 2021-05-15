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
        $validator = Validator::make(
            $data=$request->all(), 
            $rules= [
                'name' => ['required', 'string'],
                'price' => ['required', 'integer'],
                'is_available' => ['required', 'boolean'],
                'img_url' => ['nullable',' string'],
                'type_menu_id' => ['required', 'integer']
            ]
        );
        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json($message, 400);
        }

        $type_menu = TypeMenu::find($request->get('type_menu_id'));
        if ($type_menu === null) {
            return response()->json([
                'type_menu_id' => ['type_menu_id not found']
            ], 400);
        }

        try {
            $new_menu = new Menu;
            $new_menu->name = $request->get('name');
            $new_menu->price = $request->get('price');
            $new_menu->is_available = $request->get('is_available');
            $new_menu->img_url = $request->get('img_url');
            $new_menu->type_menu_id = $request->get('type_menu_id');
            $new_menu->save();
        } catch (QueryException $e) {
            return response()->json(['message' => $e], 500);
        }

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
        $validator = Validator::make(
            $data=$request->all(), 
            $rules= [
                'name' => ['required', 'string'],
                'price' => ['required', 'integer'],
                'is_available' => ['required', 'boolean'],
                'img_url' => ['nullable',' string'],
                'type_menu_id' => ['required', 'integer']
            ]
        );
        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json($message, 400);
        }

        $type_menu = TypeMenu::find($request->get('type_menu_id'));
        if ($type_menu === null) {
            return response()->json([
                'type_menu_id' => ['type_menu_id not found']
            ], 400);
        }

        try {
            $menu->name = $request->get('name');
            $menu->price = $request->get('price');
            $menu->is_available = $request->get('is_available');
            $menu->img_url = $request->get('img_url');
            $menu->type_menu_id = $request->get('type_menu_id');
            $menu->save();
        } catch (QueryException $e) {
            return response()->json(['message' => $e], 500);
        }

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
