<?php

namespace App\Http\Controllers;

use App\Repository\UserRepositoryInterface;
use App\Http\Requests\UserValidation;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // private $userRepository;

    // public function __construct(UserRepositoryInterface $userRepository)
    // {
    //     $this->userRepository = $userRepository;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->userRepository->all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserValidation $request)
    {
        if(auth()->user()->can('add-user')){
            $validated = $request->validated();
        
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create(
                $validated
            );
            
             return response()->json(['user_id' => $user->id]);
            //return response()->json($this->userRepository->create($validated), 200);
        }
        
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        if(auth()->user()->can('view')){
            $user = User::findOrFail($id);
            
            return response()->json($user);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserValidation $request, $id)
    {
        if(auth()->user()->can('update-user')){
            $validated = $request->validated();
                
            $user = User::findOrFail($id);

            if($validated['password'] === null) {
                $validated['password'] = $user->password;
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user->update($validated);

            return response()->json($user, 201);
        }
        
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->can('delete-user')){
            $user = User::findOrFail($id);
            $user->delete();
            
            return response()->json('User deleted');
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
