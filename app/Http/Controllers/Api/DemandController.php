<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use \Auth;
use App\Demand;
use Gate;

class DemandController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    protected function validator(array $data)
    {
        $message = [
            'description.required'              => 'descrição é requerida',
            'address.required'                  => 'Endereço é requerido',
            'contact.required'                  => 'Contato é requerido',
            'status.required'                   => 'Status é requerido',

            'description.max'                   => 'tamanho máximo para descrição é de 500 caracteres',
            'address.max'                       => 'tamanho máximo para Endereço é de 150 caracteres',
            'contact.max'                       => 'tamanho máximo para Contato é de 150 caracteres',
        ];
        return Validator::make($data, [
            'description'                       => 'required|max:500',
            'address'                           => 'required|max:150',
            'contact'                           => 'required|max:150',
            'status'                            => 'required'
            
        ], $message);
    }

    public function index(Demand $demand)
    {
        $this->hasUser();
        $id = auth()->user()->id;
        
        //dd(auth()->user());

        $getDemand = $demand->all();

        // $getDemand = $demand->where('user_id', "=" , "$id")->get();
        if(auth()->user()->can('adm', $getDemand)){
            return response()->json(['success' => true, 'demand' => $getDemand]);
        }else{
            $getDemand = $demand->where('user_id', "=" , "$id")->get();
            if(auth()->user()->can('read', $getDemand)){
                return response()->json(['success' => true, 'demand' => $getDemand]);
            }else{
                return response()->json(['success' => false]);
            }
        }
        
    }

    public function hasUser(){
        if(!auth()->user()){
            return response()->json(['success' => false, 'user' => 'usuario não logado']);
        }
    }

    public function store(Request $request)
    {
        try{
            $this->hasUser();

            $demand = new Demand($request->all());

            if(auth()->user()->can('write', $demand)){
                $validator = $this->validator($request->all());

                if ($validator->fails())
                {
                    return response()->json(['success' => false, 'error' => $validator->errors()], 400);
                }

                $demand->user_id = auth()->user()->id;
                
                if($demand->save()){
                    return response()->json(['success' => true, 'demand' => $demand]);
                }else{
                    return response()->json(['success' => false, 'error' => 'Ocorreu um erro durante a transação do banco de dados'], 400);
                }
            }else{
                return response()->json(['success' => false, 'error' => 'Não autorizado'], 403);
            }
        }catch (\Exception $e){
            \Log::info($e);
            return response()->json(['success' => false, 'error' => $e], 400);
        }
    }

    public function edit(Request $request, $id)
    {
        try{
            $this->hasUser();

            $idUser = auth()->user()->id;

            $demand = Demand::find($id);

            
            if($demand->user_id != $idUser){
                if(!auth()->user()->can('adm', $demand)){
                    return response()->json(['success' => false, 'erro' => 'Não autorizado'], 403);
                }
            }

            $demand->fill($request->all());
            
            if(auth()->user()->can('edit', $demand) || auth()->user()->can('adm', $demand)){   
                if($demand->save()){
                    return response()->json(['success' => true, 'demand' => $demand]);
                }else{
                    return response()->json(['success' => false, 'error' => 'Ocorreu um erro durante a transação do banco de dados'], 400);
                }
            }else{
                return response()->json(['success' => false, 'error' => 'Não autorizado'], 403);
            }
        }catch (\Exception $e){
            \Log::info($e);
            return response()->json(['success' => false, 'error' => $e], 400);
        }
    }

    public function changeStatus($id){
        try{
            $this->hasUser();

            $demand = Demand::find($id);
            $idUser = auth()->user()->id;

            if($demand->user_id != $idUser){
                if(!auth()->user()->can('adm', $demand)){
                    return response()->json(['success' => false, 'erro' => 'Não autorizado'], 403);
                }
            }

            if(auth()->user()->can('finalize', $demand) || auth()->user()->can('adm', $demand)){
                $demand->status = true;

                if($demand->save()){
                    return response()->json(['success' => true, 'demand' => $demand]);
                }else{
                    return response()->json(['success' => false, 'error' => 'Ocorreu um erro durante a transação do banco de dados'], 400);
                }
            }else{
                return response()->json(['success' => false, 'error' => 'Não autorizado'], 403);
            }
        }catch (\Exception $e){
            \Log::info($e);
            return response()->json(['success' => false, 'error' => $e], 400);
        }
    }

    public function rolesPermission(){
        $name = auth()->user()->name;
        echo "<h2>$name</h2>";

        foreach(auth()->user()->roles as $role){
            echo "<b>$role->name</b> -> ";

            $permissions = $role->permissions;
            foreach($permissions as $permission){
                echo "$permission->name , ";
            }
            echo "<hr>";
        }
    }
}
