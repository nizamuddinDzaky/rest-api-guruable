<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    /*public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'user_username' => 'required|string|unique:users',
            'password' => 'required|confirmed',
        ]);

        try 
        {
            $user = new User;
            $user->username= $request->input('username');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            return response()->json( [
                        'entity' => 'users', 
                        'action' => 'create', 
                        'result' => 'success'
            ], 201);

        } 
        catch (\Exception $e) 
        {
            return response()->json( [
                       'entity' => 'users', 
                       'action' => 'create', 
                       'result' => 'failed'
            ], 409);
        }
    }*/
	
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */	 
    public function login(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'user_username' => 'required|string',
                'password' => 'required|string',
                'user_role_id'=> 'required|numeric',
            ]);
    
            $credentials = $request->only(['user_username', 'password', 'user_role_id']);
            if (! $token = Auth::attempt($credentials)) {			
                throw new \Exception("User Tidak Ditemukan");
            }
            $user = auth()->user();
    
            if($user->user_status_verifikasi != 1){
                throw new \Exception("Data ID Lokasi Akun Belum Terveifikasi");
            }
            
            $additional_data_user = [];
            if($user->user_role_id == 1){
                $additional_data_user = $user->admin;    
            }
            $response = [
                'token'=> $token,
                'data_user' => $user,
                'additional_data_user'=>$additional_data_user
            ];
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $response, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
        // return $this->respondWithToken($token);
    }
	
     /**
     * Get user details.
     *
     * @param  Request  $request
     * @return Response
     */	 	
    public function me()
    {
        return response()->json(auth()->user());
    }
}