<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Demand;
use Gate;

class HomeController extends Controller
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
    public function index(Demand $demand)
    {
        $posts = $demand->all();

        if(!auth()->user()){
            return response()->json(['success' => false, 'user' => 'usuario não logado']);
        }

        // if(auth()->user()->can('view', $posts)){
        //     return response()->json(['success' => true, 'post' => $posts]);
        // }else{
        //     return response()->json(['success' => false]);
        // }
        
        $posts = $demand->where('user_id', auth()->user()->id)->get();

        return view('home', compact('posts'));
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
