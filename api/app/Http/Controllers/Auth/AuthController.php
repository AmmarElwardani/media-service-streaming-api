<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

use App\Models\User;
use App\Http\Requests\UserValidation;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{

    
    /**
     * Create a new user instance after a valid registration.
     * 
     * @param array $data
     * @return \App\Models\User
     */
    public function register(UserValidation $request){

        
        $user = $this->create($request);

        event(new Registered($user));

        $this->login($request);

        //return redirect($this->redirectPath());
        return (response()->json(['response' => '201']));

    }

    public function create(UserValidation $request)
    {
            $validated = $request->validated();
            
            $validated['password'] = Hash::make($validated['password']);

            return User::create(
                $validated
            );
            
            //return response()->json(['user_id' => $user->id]);
            //return response()->json($this->userRepository->create($validated), 200);
        
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            return response()->json(compact('token'));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

     /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function getAuthenticatedUser(Request $request){
        
        $user = $request->user();

        try{
            
            if(! $user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
        }
}
