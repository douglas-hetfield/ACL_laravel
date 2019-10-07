<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
       
        if (Auth()->attempt($credentials)) {
            // Authentication passed...
            
            $user = Auth()->user();
            $token = Str::random(60);
            $user->api_token = hash('sha256', $token);
  
            if($user->save()){
                return response()->json(['success' => true, 'user' => $user, 'token' => $token]);
            }else{
                return response()->json(['success' => false, 'erro ao autenticar o usuario']);
            }
        }else{
            return response()->json(['success' => false, 'message' => 'Erro ao autenticar verifique seu email e sua senha.']);
        }
    } 


    public function index(){
        return response()->json(User::get()); 
    }

    public function show($id){
        
        $user = User::find($id);

        if ($user){
            return response()->json(['success' => true, 'user' => $user], 200);
        } else {
            return response()->json(['success' => false, 'error' => 'Usuário não encontrado.'], 404);
        }
    }

    //não esta fazendo o update!
    public function update(Request $request, $id){
        try {
            $user = User::find($id);
            $request->password = bcrypt($request->password);
            $user->fill($request->all());

            if ($user->save()){
                return response()->json(['success' => true, 'user' => $user]);
            } else {
                return response()->json(['success' => false, 'error' => ['Erro inesperado']], 400);
            }
        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json(['success' => false, 'error' => $e], 400);
        }
    }

    public function destroy($id){
        try {
            $user = User::find($id);

            if ($user->delete()){
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'error' => ['Erro inesperado']], 400);
            }

        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json(['success' => false, 'error' => $e], 400);
        }
    }

    public function store(Request $request){
        $user = new User($request->all());
        $user->password = bcrypt($user->password);
        try {
            if ($user->save()){
                return response()->json(['success' => true, 'user' => $user]);
            } else {
                return response()->json(['success' => false, 'error' => ['Erro inesperado']], 400);
            }
        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json(['success' => false, 'error' => $e], 400);
        }
    }
}
