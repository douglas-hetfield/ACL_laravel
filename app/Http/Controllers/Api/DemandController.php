<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            'advertiser.required'               => 'Anunciante é requerido',
            'status.required'                   => 'Status é requerido',

            'description.max'                   => 'tamanho máximo para descrição é de 500 caracteres',
            'address.max'                       => 'tamanho máximo para Endereço é de 150 caracteres',
            'contact.max'                       => 'tamanho máximo para Contato é de 150 caracteres',
            'advertiser.max'                    => 'tamanho máximo para Contato é de 150 caracteres'
        ];
        return Validator::make($data, [
            'description'                       => 'required|max:500',
            'address'                           => 'required|max:150',
            'contact'                           => 'required|max:150',
            'advertiser'                        => 'required|max:150',
            'status'                            => 'required'
            
        ], $message);
    }

    public function index(Demand $demand)
    {
        $posts = $demand->all();

        if(!auth()->user()){
            return response()->json(['success' => false, 'user' => 'usuario não logado']);
        }

        if(auth()->user()->can('read', $posts)){
            return response()->json(['success' => true, 'post' => $posts]);
        }else{
            return response()->json(['success' => false]);
        }
        
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails())
        {
            return response()->json(['success' => false, 'error' => $validator->errors()], 400);
        }

        $participant = Participant::where('email', $request->email)
                                  ->orWhere('phone', $request->phone)
                                  ->first();
    }

    public function edit($id)
    {
        //
    }

    public function changeStatus(Request $request){

    }







    public function update($idPost){
        $post = Demand::find($idPost);
        
        //$this->authorize('update-post', $post);
        if(Gate::denies('write', $post)){
            abort(403, "Não autorizado");
        }


        return view('post-update', compact('post'));
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
