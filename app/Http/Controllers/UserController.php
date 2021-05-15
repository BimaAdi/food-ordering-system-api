<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function GuzzleHttp\Promise\exception_for;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $size = $request->input($key='size', $default='10');
        return User::with('role')->paginate($size);
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
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed',
                'role_id' => 'required|integer'
            ]
        );
        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json($message, 400);
        }

        $data = $validator->validate();
        $role = Role::find($data['role_id']);
        if ($role === null) {
            return response()->json([
                'role_id' => ['role_id not found']
            ], 400);
        }
        
        try {
            $data = $validator->validate();
            $new_user = new User;
            $new_user->name = $data['name'];
            $new_user->email = $data['email'];
            $new_user->password = Hash::make($data['password']);
            $new_user->role_id = $data['role_id'];
            $new_user->save();
        } catch (QueryException $e) {
            return response()->json(['message' => $e], 500);
        }
        

        return response()->json($new_user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return User::with('role')->find($user->id)->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make(
            $data=$request->all(), 
            $rules= [
                'name' => ['required', 'string'],
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => ['confirmed'],
                'role_id' => ['required', 'integer']
            ]
        );
        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json($message, 400);
        }
        $data = $validator->validate();
        $role = Role::find($data['role_id']);
        if ($role === null) {
            return response()->json([
                'role_id' => ['role_id not found']
            ], 400);
        }

        try {
            $user->name = $request->get('name', $user->name);
            $user->email = $request->get('email', $user->email);
            if ($request->get('password') !== null) {
                $user->password = Hash::make($request->get('password'));
            }
            $user->role_id = $request->get('role_id', $user->role_id);
            $user->save();
        } catch (QueryException $e) {
            return response()->json(['message' => $e], 500);
        }
        
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json('', 204);
    }
}
